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

$errorMsg = '';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        if(isset($_REQUEST['title']) 
        && isset($_REQUEST['author'])
        && isset($_REQUEST['genre'])
        && isset($_REQUEST['price'])){
            $newTitle = htmlspecialchars($_REQUEST['title']);
            $newAuthor = htmlspecialchars($_REQUEST['author']);
            $newGenre = htmlspecialchars($_REQUEST['genre']);
            $newPrice = (float)htmlspecialchars($_REQUEST['price']);
            $newItem = [
                'title' => $newTitle, // prevent xss
                'author' => $newAuthor,
                'genre' => $newGenre,
                'price' =>  $newPrice// convert to num(float)
            ];
            $books[] = $newItem;
            // call applying discounts function
            applyDiscounts($books);

            // append log
            $ip = $_SERVER['REMOTE_ADDR'];
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $timestamp = date('Y-m-d H:i:s');
            $log = "[$timestamp] IP: $ip | UA: [$userAgent] | Added book: \"$newTitle\" ($newGenre, $newPrice)\n";
            file_put_contents('./bookstore_log.txt', $log, FILE_APPEND);
        }
        else{
            http_response_code(400); // Bad Request
            $errorMsg = "Required keys not found";
            echo $errorMsg;
        }
        break;
    case "GET":
        break;
    default:
        http_response_code(400); // Bad Request
        $errorMsg = "VERY VERY BAD REQUEST :(";
}

// call applying discounts function
applyDiscounts($books);



?>