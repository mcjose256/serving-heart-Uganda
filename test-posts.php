<?php
// Test file to check database connection and posts
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection & Posts Test</h2>";

// Test 1: Check if db.php exists
if (file_exists('includes/db.php')) {
    echo "✅ includes/db.php file exists<br><br>";
} else {
    echo "❌ includes/db.php file NOT FOUND!<br><br>";
    exit;
}

// Include database connection
require_once 'includes/db.php';

// Test 2: Check PDO connection
if (isset($pdo)) {
    echo "✅ Database connected successfully<br><br>";
} else {
    echo "❌ Database connection failed<br><br>";
    exit;
}

// Test 3: Check if posts table exists
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'posts'");
    if ($checkTable->rowCount() > 0) {
        echo "✅ 'posts' table exists<br><br>";
    } else {
        echo "❌ 'posts' table NOT FOUND!<br><br>";
        exit;
    }
} catch (PDOException $e) {
    echo "❌ Error checking table: " . $e->getMessage() . "<br><br>";
    exit;
}

// Test 4: Count total posts
try {
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM posts");
    $count = $countStmt->fetch();
    echo "📊 Total posts in database: <strong>" . $count['total'] . "</strong><br><br>";
} catch (PDOException $e) {
    echo "❌ Error counting posts: " . $e->getMessage() . "<br><br>";
    exit;
}

// Test 5: Count published posts
try {
    $publishedStmt = $pdo->query("SELECT COUNT(*) as total FROM posts WHERE status = 'published'");
    $publishedCount = $publishedStmt->fetch();
    echo "📊 Published posts: <strong>" . $publishedCount['total'] . "</strong><br><br>";
} catch (PDOException $e) {
    echo "❌ Error counting published posts: " . $e->getMessage() . "<br><br>";
    exit;
}

// Test 6: Fetch latest 3 published posts
try {
    $sql = "SELECT id, title, slug, excerpt, category, published_at FROM posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 3";
    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll();
    
    echo "<h3>Latest 3 Published Posts:</h3>";
    
    if (empty($posts)) {
        echo "⚠️ <strong>No published posts found!</strong><br><br>";
        
        // Check for draft posts
        $draftStmt = $pdo->query("SELECT COUNT(*) as total FROM posts WHERE status = 'draft'");
        $draftCount = $draftStmt->fetch();
        
        if ($draftCount['total'] > 0) {
            echo "💡 Found " . $draftCount['total'] . " draft posts. They need to be published.<br><br>";
        }
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>
                <th>ID</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Published</th>
                <th>Excerpt</th>
              </tr>";
        
        foreach ($posts as $post) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($post['id']) . "</td>";
            echo "<td>" . htmlspecialchars($post['title']) . "</td>";
            echo "<td>" . htmlspecialchars($post['slug']) . "</td>";
            echo "<td>" . htmlspecialchars($post['category']) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($post['published_at'])) . "</td>";
            echo "<td>" . substr(htmlspecialchars($post['excerpt']), 0, 100) . "...</td>";
            echo "</tr>";
        }
        
        echo "</table><br>";
        echo "✅ <strong style='color: green;'>Posts are being fetched correctly!</strong><br><br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error fetching posts: " . $e->getMessage() . "<br><br>";
    exit;
}

// Test 7: Check fetchAll function
echo "<h3>Testing Helper Functions:</h3>";

if (function_exists('fetchAll')) {
    echo "✅ fetchAll() function exists<br>";
    
    try {
        $testPosts = fetchAll($pdo, "SELECT * FROM posts WHERE status = 'published' LIMIT 3");
        echo "✅ fetchAll() returned " . count($testPosts) . " posts<br><br>";
    } catch (Exception $e) {
        echo "❌ fetchAll() error: " . $e->getMessage() . "<br><br>";
    }
} else {
    echo "❌ fetchAll() function NOT FOUND!<br><br>";
}

// Summary
echo "<hr>";
echo "<h3>Summary:</h3>";
if (!empty($posts)) {
    echo "✅ <strong style='color: green; font-size: 18px;'>Everything is working! Posts should display on homepage.</strong><br><br>";
    echo "<a href='index.php' style='background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Homepage</a>";
} else {
    echo "⚠️ <strong style='color: orange; font-size: 18px;'>No published posts found. Need to add or publish posts.</strong><br><br>";
    echo "Next steps:<br>";
    echo "1. Check phpMyAdmin to see if posts exist<br>";
    echo "2. Run the database import again<br>";
    echo "3. Or manually insert posts via SQL<br>";
}

echo "<br><br><p style='color: red;'><strong>IMPORTANT:</strong> Delete this test file after debugging for security!</p>";
?>