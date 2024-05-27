<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinite Scroll Pagination</title>
    
</head>
<body>
    <?include "header.php";?>
    <div class="grid-wrapper">
        <?php
            require_once 'load_more_data.php';
        ?>
        </div>
    <div id="postContainer">
        
        <button id="loadMoreBtn">Load More</button>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var offset = 0;
            $('#loadMoreBtn').on('click', function() {
                offset += 5;
                $.ajax({
                    type: 'GET',
                    url: 'load_more_data.php',
                    data: {offset: offset},
                    success: function(data) {
                        $('.grid-wrapper').append(data);
                    }
                });
            });
        });
    </script>
</body>
</html>