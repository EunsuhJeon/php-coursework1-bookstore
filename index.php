<?php
// -------------------------
// books
// -------------------------
$books = [
    [
        'title' => 'Dune',
        'author' => 'Frank Herbert',
        'genre' => 'Science Fiction',
        'price' => 29.99
    ],
    [
        'title' => 'The Three-Body Problem',
        'author' => 'Liu Cixin',
        'genre' => 'Science Fiction',
        'price' => 31.99
    ],
    [
        'title' => 'The Silence of the Lambs',
        'author' => 'Thomas Harris',
        'genre' => 'Suspense',
        'price' => 22.90
    ],
    [
        'title' => 'The Lord of the Rings',
        'author' => 'J. R. R. Tolkien',
        'genre' => 'fantasy',
        'price' => 27.79
    ],
    [
        'title' => 'The Chronicles of Narnia',
        'author' => 'C. S. Lewis',
        'genre' => 'fantasy',
        'price' => 34.00
    ],
];
function applyDiscounts(array &$books) {
    foreach ($books as &$book) {
        if($book['genre'] === 'Science Fiction'){
            // 10% discount
            $book['price'] = $book['price']*0.9;
        }
    }
    unset($book);
}
?>