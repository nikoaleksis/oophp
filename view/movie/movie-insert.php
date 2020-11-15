<form method="post">
    <fieldset>
    <legend>Edit</legend>
    <input type="hidden" name="movieId" value=""/>

    <p>
        <label>Title:<br>
        <input type="text" name="movieTitle" value=""/>
        </label>
    </p>

    <p>
        <label>Year:<br>
        <input type="number" name="movieYear" value=""/>
    </p>

    <p>
        <label>Image:<br>
        <input type="text" name="movieImage" value="img/noimage.png/">
        </label>
    </p>

    <p>
        <input type="submit" name="doSave" value="Save">
        <input type="reset" value="Reset">
    </p>
    <p>
        <a href="?showAll=true">Show all</a>
    </p>
    </fieldset>
</form>
