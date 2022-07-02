<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap CDN-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <title>Admin-Account</title>

</head>

<body>

    <table class='table table-striped table-hover'  
    id="books_table" 
    data-show-fullscreen="true" 
    data-show-columns="true" 
    data-show-pagination-switch="true"
    data-pagination="true"
    data-show-columns-toggle-all="true"
    data-filter-control="true"
    data-show-search-clear-button="true">
        <thead>
            <tr>
                <th scope="col" sortable='true', filterControl='input', filterStrictSearch='false'>ID #</th>
                <th scope="col">Postcard Name</th>
                <th scope="col">Front Image</th>
                <th scope="col">Back Image</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Artist</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php include('./includes/admin-postcard.ini.php');
            ?>
            
        </tbody>
    </table>
</body>

</html>