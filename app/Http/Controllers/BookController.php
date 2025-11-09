<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use App\Enums\BookCopiesEnums;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author', 'category', 'copies')->paginate(20);
        return response()->json(['data' => $books], 200);
    }

    public function show($id)
    {
        $book = Book::with(['author', 'category', 'copies'])->findOrFail($id);
        return response()->json(['data' => $book], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author_id' => 'nullable|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'copies' => 'nullable|array',
            'copies.*.barcode' => 'nullable|string|distinct',
            'copies.*.status' => 'nullable|in:available,borrowed,reserved,library_only,damaged,lost'
        ], [
            'title.required' => 'عنوان کتاب الزامی است.',
            'author_id.exists' => 'نویسنده انتخاب شده معتبر نیست.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
            'copies.array' => 'نسخه‌های کتاب باید یک آرایه باشند.',
            'copies.*.barcode.distinct' => 'بارکد نسخه‌ها تکراری است.',
            'copies.*.status.in' => 'وضعیت نسخه نامعتبر است.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = Book::create($request->only(['title', 'author_id', 'category_id', 'description']));

        if (!empty($request->copies)) {
            foreach ($request->copies as $c) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'barcode' => $c['barcode'] ?? null,
                    'status' => $c['status'] ?? BookCopiesEnums::AVAILABLE
                ]);
            }
        }

        return response()->json(['message' => 'کتاب با موفقیت ایجاد شد', 'data' => $book->load('copies')], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'author_id' => 'nullable|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string'
        ], [
            'title.required' => 'عنوان کتاب الزامی است.',
            'author_id.exists' => 'نویسنده انتخاب شده معتبر نیست.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book->update($request->only(['title', 'author_id', 'category_id', 'description']));
        return response()->json(['message' => 'کتاب با موفقیت ویرایش شد', 'data' => $book->fresh('copies')]);
    }

    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
        return response()->json(['message' => 'کتاب با موفقیت حذف شد'], 200);
    }
}
