<?php
session_start();
include('includes/config.php');

$bookid = intval($_GET['bookid']);
$sql = "SELECT digital_file, BookName FROM tblbooks WHERE id=:bookid";
$query = $dbh->prepare($sql);
$query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

if(!$result || $result->digital_file == "") {
    die("Preview not available for this book.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Preview: <?php echo $result->BookName; ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        body { background: #333; margin: 0; color: white; font-family: sans-serif; }
        .toolbar { position: fixed; top: 0; width: 100%; background: #1a1a1a; padding: 15px; text-align: center; z-index: 10; box-shadow: 0 2px 10px rgba(0,0,0,0.5); }
        #pdf-container { margin-top: 80px; display: flex; flex-direction: column; align-items: center; padding-bottom: 50px; }
        canvas { margin-bottom: 20px; box-shadow: 0 0 20px rgba(0,0,0,0.6); max-width: 90%; }
        .limit-msg { background: white; color: #333; padding: 40px; border-radius: 8px; text-align: center; max-width: 500px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="toolbar">
    <strong>Reading: <?php echo htmlentities($result->BookName); ?></strong> 
    <span style="margin-left: 20px;">(Free 12-Page Preview)</span>
</div>

<div id="pdf-container"></div>

<script>
    const url = 'admin/bookfiles/<?php echo $result->digital_file; ?>';
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

    pdfjsLib.getDocument(url).promise.then(pdf => {
        const container = document.getElementById('pdf-container');
        // Limit to 12 pages
        const pagesToShow = Math.min(pdf.numPages, 12);

        for (let i = 1; i <= pagesToShow; i++) {
            pdf.getPage(i).then(page => {
                const scale = 1.5;
                const viewport = page.getViewport({ scale });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                container.appendChild(canvas);
                page.render({ canvasContext: context, viewport: viewport });
            });
        }

        // After 12 pages, show the "Buy/Borrow" message
        if (pdf.numPages > 12) {
            const msg = document.createElement('div');
            msg.className = 'limit-msg';
            msg.innerHTML = `<h3>Want to keep reading?</h3><p>This is just a 12-page preview. Please visit the library to borrow the full book.</p>`;
            container.appendChild(msg);
        }
    });
</script>
</body>
</html>