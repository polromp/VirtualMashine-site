<?php
$term = $_GET['query'] ?? ''; 
$discipline_path = 'terms/';  

if (empty($term)) {
    echo '';
    exit;
}

$files = [];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($discipline_path));

foreach ($iterator as $file) {
    if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'txt') {
        $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

        if (stripos($filename, $term) !== false) {
            $relativePath = str_replace('\\', '/', $file->getRealPath());
            $discipline = basename(dirname(dirname($relativePath)));  
            
            $subsection = basename(dirname($relativePath));  
            $termName = $filename;  
            
            $url = "termin.php?discipline=" . urlencode( $discipline) . "&subsection=" . urlencode($subsection) . "&term=" . urlencode($termName);

            $files[] = "<li data-path='{$url}'>{$termName}</li>";
        }
    }
}

if (!empty($files)) {
    echo '<ul>' . implode('', $files) . '</ul>';
} else {
    echo '<li>Термин не найден</li>';
}
?>
