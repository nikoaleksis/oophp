<h1>Showing off BBCode</h1>
<h2>Source in bbcode.txt</h2>
<pre><?= wordwrap(htmlentities($text)) ?></pre>

<h2>Filter BBCode applied, source</h2>
<pre><?= wordwrap(htmlentities($bbcodeToHtml)) ?></pre>

<h2>BBCODE Applied</h2>
<pre><?= $bbcodeToHtml ?></pre>

<h2>BBCODE NL2bR (SOURCE)</h2>
<pre><?= wordwrap(htmlentities($htmlToNl2br)) ?></pre>
<h2>Filter BBCode applied, HTML (including nl2br())</h2>
<?= $htmlToNl2br ?>
