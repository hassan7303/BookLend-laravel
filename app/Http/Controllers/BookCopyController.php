<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use Illuminate\Http\Request;
use App\Enums\BookCopiesEnums;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookCopyController extends Controller
{
    public function index()
    {
        $copies = BookCopy::with('book')->paginate(30);
        return response()->json(['data' => $copies]);
    }

    public function show($id)
    {
        $copy = BookCopy::with('book')->findOrFail($id);
        return response()->json(['data' => $copy]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'barcode' => 'nullable|unique:book_copies,barcode',
            'status' => 'nullable|in:available,borrowed,reserved,library_only,damaged,lost'
        ], [
            'book_id.required' => 'کتاب الزامی است.',
            'book_id.exists' => 'کتاب انتخاب شده معتبر نیست.',
            'barcode.unique' => 'بارکد وارد شده تکراری است.',
            'status.in' => 'وضعیت وارد شده معتبر نیست.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $copy = BookCopy::create([
            'book_id' => $request->book_id,
            'barcode' => $request->barcode ?? null,
            'status' => $request->status ?? BookCopiesEnums::AVAILABLE
        ]);

        return response()->json(['message' => 'نسخه کتاب با موفقیت ایجاد شد', 'data' => $copy], 201);
    }

    public function update(Request $request, $id)
    {
        $copy = BookCopy::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'barcode' => 'nullable|unique:book_copies,barcode,' . $id,
            'status' => 'nullable|in:available,borrowed,reserved,library_only,damaged,lost'
        ], [
            'barcode.unique' => 'بارکد وارد شده تکراری است.',
            'status.in' => 'وضعیت وارد شده معتبر نیست.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $copy->update($request->only(['barcode', 'status']));
        return response()->json(['message' => 'نسخه کتاب با موفقیت ویرایش شد', 'data' => $copy]);
    }

    public function setLibraryOnly($id)
    {
        $copy = BookCopy::findOrFail($id);
        $copy->status = BookCopiesEnums::LIBRARY_ONLY;
        $copy->save();

        return response()->json(['message' => 'نسخه کتاب به حالت فقط کتابخانه تغییر یافت', 'data' => $copy]);
    }
}
