<form method="get">
    <fieldset>
    <legend>Search</legend>
    <input type="hidden" name="route" value="search-year">
    <p>
        <label>Created between:
        <input type="number" name="fromYear" value="<?= htmlentities($fromYear) ?: 1900 ?>" min="1900" max="2100"/>
        -
        <input type="number" name="toYear" value="<?= htmlentities($toYear)  ?: 2100 ?>" min="1900" max="2100"/>
        </label>
    </p>
    <p>
        <input type="submit" name="doSearch" value="Search">
    </p>
    <p><a href="?fromYear=1900&toYear=2100">Show all</a></p>
    </fieldset>
</form>
