<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Count in Files</title>
</head>
<body>
    <h1>Count Word in Files</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="word">Enter the word you want to search for:</label><br>
        <input type="text" id="word" name="word" required><br><br>
        <input type="submit" value="Search">
    </form>
    
    <h2>Output:</h2>
    <pre>
        <?php
        function count_word_in_file($filepath, $word) {
            if (!file_exists($filepath)) {
                return 0;
            }
            $content = file_get_contents($filepath);
            return substr_count(strtolower($content), strtolower($word)); // Count case-insensitively
        }

        function find_file_with_highest_word_count($directory, $word) {
            $highest_count = 0;
            $best_file = null;

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $count = count_word_in_file($file->getPathname(), $word);
                    if ($count > $highest_count) {
                        $highest_count = $count;
                        $best_file = $file->getPathname();
                    }
                }
            }

            return [$best_file, $highest_count];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $word = trim($_POST['word']);
            $directory = 'rootconsonants'; // Directory to search in
            
            // Check if a file with the exact name (case insensitive) exists
            $exact_file_path = $directory . '/' . strtolower($word) . '.txt';
            if (file_exists($exact_file_path)) {
                // Read and display this file's content directly.
                $data = file_get_contents($exact_file_path);
                echo $data;
            } else {
                if (is_dir($directory)) {
                    list($best_file, $count) = find_file_with_highest_word_count($directory, $word);

                    if ($best_file) {
                        // Now you can read and print the contents of the best file
                        $data = file_get_contents($best_file);
                        echo $data;
                    } else {
                        echo "The word '{$word}' was not found in any file in the '{$directory}' directory.";
                    }
                } else {
                    echo "Error: The directory '{$directory}' does not exist.";
                }
            }
        }
        ?>
    </pre>
</body>
</html>