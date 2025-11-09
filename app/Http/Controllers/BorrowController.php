<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fine;
use App\Models\Borrow;
use App\Models\BookCopy;
use App\Models\FineRule;
use App\Enums\BorrowsEnums;
use Illuminate\Http\Request;
use App\Enums\BookCopiesEnums;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BorrowController extends Controller
{
    // Borrow a single copy
    public function borrow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_copy_id' => 'required|exists:book_copies,id',
            'days' => 'required|integer|min:1|max:365'
        ], [
            'book_copy_id.required' => 'نسخه کتاب الزامی است.',
            'book_copy_id.exists' => 'نسخه انتخاب شده معتبر نیست.',
            'days.required' => 'تعداد روز امانت الزامی است.',
            'days.integer' => 'تعداد روز باید عدد صحیح باشد.',
            'days.min' => 'حداقل یک روز باید مشخص شود.',
            'days.max' => 'حداکثر 365 روز می‌توانید مشخص کنید.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $copy = BookCopy::findOrFail($request->book_copy_id);

        if ($copy->status !== BookCopiesEnums::AVAILABLE) {
            return response()->json(['message' => 'این نسخه در دسترس نیست.'], 400);
        }

        // mark as borrowed
        $copy->status = BookCopiesEnums::BORROWED;
        $copy->save();

        $borrowDate = Carbon::today();
        $dueDate = Carbon::today()->addDays($request->days);

        $borrow = Borrow::create([
            'user_id' => $request->user()->id,
            'book_copy_id' => $copy->id,
            'borrow_date' => $borrowDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'status' => BorrowsEnums::BORROWED
        ]);

        return response()->json(['message' => 'کتاب با موفقیت امانت گرفته شد', 'data' => $borrow->load('bookCopy')], 201);
    }

    public function return(Request $request, $borrowId)
    {
        $borrow = Borrow::findOrFail($borrowId);

        if ($borrow->status !== BorrowsEnums::BORROWED) {
            return response()->json(['message' => 'این امانت قبلاً بازگردانده شده یا وضعیت نامعتبر است.'], 400);
        }

        $borrow->return_date = Carbon::today()->toDateString();

        $due = Carbon::parse($borrow->due_date);
        $today = Carbon::today();

        if ($today->gt($due)) {
            $daysLate = $due->diffInDays($today);
            $rule = FineRule::where('active', true)->latest()->first();
            $amount = $rule ? $rule->per_day_amount * $daysLate : 0;

            Fine::create([
                'borrow_id' => $borrow->id,
                'days_late' => $daysLate,
                'amount' => $amount,
                'paid' => false
            ]);

            $borrow->status = BorrowsEnums::LATE;
        } else {
            $borrow->status = BorrowsEnums::RETURNED;
        }

        $borrow->save();

        // mark copy available again (unless special status)
        $copy = $borrow->bookCopy;
        if (in_array($copy->status, [BookCopiesEnums::BORROWED, BookCopiesEnums::RESERVED])) {
            $copy->status = BookCopiesEnums::AVAILABLE;
            $copy->save();
        }

        return response()->json([
            'message' => 'کتاب با موفقیت بازگردانده شد',
            'data' => $borrow->load('bookCopy', 'user')
        ]);
    }

    public function myBorrows(Request $request)
    {
        $borrows = Borrow::with('bookCopy.book')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json(['data' => $borrows]);
    }
}
