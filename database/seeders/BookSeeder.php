<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            ['title' => 'Harry Potter and the Sorcerer\'s Stone', 'author' => 'J.K. Rowling', 'publisher' => 'Bloomsbury', 'year' => 1997, 'stock' => 5],
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'publisher' => 'J.B. Lippincott & Co.', 'year' => 1960, 'stock' => 3],
            ['title' => '1984', 'author' => 'George Orwell', 'publisher' => 'Secker & Warburg', 'year' => 1949, 'stock' => 4],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'publisher' => 'Charles Scribner\'s Sons', 'year' => 1925, 'stock' => 2],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'publisher' => 'T. Egerton', 'year' => 1813, 'stock' => 6],
            ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'publisher' => 'Little, Brown and Company', 'year' => 1951, 'stock' => 3],
            ['title' => 'Lord of the Flies', 'author' => 'William Golding', 'publisher' => 'Faber & Faber', 'year' => 1954, 'stock' => 4],
            ['title' => 'Animal Farm', 'author' => 'George Orwell', 'publisher' => 'Secker & Warburg', 'year' => 1945, 'stock' => 5],
            ['title' => 'Brave New World', 'author' => 'Aldous Huxley', 'publisher' => 'Chatto & Windus', 'year' => 1932, 'stock' => 2],
            ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'publisher' => 'George Allen & Unwin', 'year' => 1937, 'stock' => 7],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create($book);
        }
    }
}
