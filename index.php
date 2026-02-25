<?php
// -------------------------
// Book inventory
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
        'genre' => 'Fantasy',
        'price' => 27.79
    ],
    [
        'title' => 'The Chronicles of Narnia',
        'author' => 'C. S. Lewis',
        'genre' => 'Fantasy',
        'price' => 34.00
    ],
];

// -------------------------
// Server Info & Timestamp
// -------------------------
$currentTime = date('Y-m-d H:i:s');
$curIpAddress = $_SERVER['REMOTE_ADDR'];
$curUserAgent = $_SERVER['HTTP_USER_AGENT'];

// -------------------------
// Apply Discounts
// -------------------------
function applyDiscounts(array &$books) {
    foreach ($books as &$book) {
        if($book['genre'] === 'Science Fiction'){
            // 10% discount
            $book['price'] = round($book['price']*0.9, 2);
        } 
        elseif ($book['genre'] === 'Fantasy') {
            // 5% discount
            $book['price'] = round($book['price']*0.95, 2);
        }
    }
    unset($book);
}

// -------------------------
// Add new Book
// -------------------------
$errors = [];
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
            $errors[] = "Required keys not found";
        }
        break;
    case "GET":
        break;
    default:
        http_response_code(400); // Bad Request
        $errors[] = "VERY VERY BAD REQUEST :(";
}

// call applying discounts function
applyDiscounts($books);

// -------------------------
// Calculate total price
// -------------------------
$total = 0;
foreach ($books as $book) {
    $total += $book['price'];
}

// -------------------------
// Show logs
// -------------------------
$logContent = '';
$logPath = './bookstore_log.txt';
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bookstore</title>
    <link href="./styles.css" rel="stylesheet">
</head>
<body>
    <h1>Online Bookstore</h1>
    <!-- success msg -->
    <?php if($message): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- error msg -->
    <?php if(!empty($errors)): ?>
        <div class="error">
            <h3>Input Error:</h3>
            <?php foreach ($errors as $err): ?>
                <p>• <?php echo $err; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- add new book -->
    <h2>Add New Book</h2>
    <form method="POST" action="">
        <label>Title:
            <input type="text" name="title" required>
        </label><br>
        <label>Author:
            <input type="text" name="author" required>
        </label><br>
        <label>Genre:
            <input type="text" name="genre" required>
        </label><br>
        <label>Price:
            <input type="number" name="price" step="0.01" required>
        </label><br>
        <button type="submit">add</button>
    </form>

    <!-- book list -->
    <h2>Book Inventory</h2>
    <table>
        <thead>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Price(after discount)</th>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                    <td><?php echo number_format(htmlspecialchars($book['price']),2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- total price -->
    <h3>Total Price (After Discount): 
        <span style="color: #005e8d;">
            <?php echo "$".number_format($total, 2); ?>
        </span>
    </h3>

    <!-- Server Info & Timestamp -->
    <h2>Server Info</h2>
    <p>Request time: <?php echo $currentTime; ?></p>
    <p>IP: <?php echo $curIpAddress; ?></p>
    <p>User agent: <?php echo htmlspecialchars($curUserAgent); ?></p>

    <!-- log content -->
    <h2>Log File</h2>
    <pre><?php echo htmlspecialchars($logContent); ?></pre>
</body>
</html>