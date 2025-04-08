<?php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .search-bar input, .search-bar select, .search-bar button {
            margin: 5px;
            padding: 10px;
        }
        .course-list {
            margin-top: 20px;
        }
        .course-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .add-btn {
            background-color: lightgreen;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .drop-btn {
            background-color: darkgreen;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Class Search</h2>
        <div class="search-bar">
            <input type="text" placeholder="Subject">
            <input type="text" placeholder="Course Number">
            <input type="date">
            <input type="date">
            <button>Search</button>
            <button>Reset</button>
        </div>
        <div class="course-list">
            <?php
                $courses = [
                    ["term" => "Summer 2025", "title" => "Introduction to African American Studies", "credits" => "3"],
                    ["term" => "Fall 2025", "title" => "Introduction to African American Studies", "credits" => "3"],
                    ["term" => "Spring 2026", "title" => "Introduction to African American Studies", "credits" => "3"]
                ];
            ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <div>
                        <strong><?php echo $course['term']; ?></strong><br>
                        <?php echo $course['title']; ?><br>
                        <?php echo $course['credits']; ?> Credits
                    </div>
                    <button class="add-btn">Add Course</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
?>