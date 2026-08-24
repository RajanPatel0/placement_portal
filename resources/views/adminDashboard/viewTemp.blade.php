<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->name ?? 'Template Viewer' }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        #container {
            position: relative;
            width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #content {
            position: relative;
            z-index: 1;
            min-height: 400px;
            padding: 20px;
            border: 1px dashed #ddd;
        }

        #drawCanvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            pointer-events: auto;
        }

        .btns {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            background: #4a6ee0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #3a5bc7;
        }

        .color-palette {
            display: flex;
            justify-content: center;
            margin: 15px 0;
            gap: 10px;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: transform 0.2s;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.active {
            border-color: #333;
            transform: scale(1.2);
        }

        .pen-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
            gap: 15px;
        }

        .pen-size {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pen-size input {
            width: 100px;
        }

        .pen-size-value {
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>{{ $template->name ?? 'Template Viewer with Drawing' }}</h2>

    <!-- Pen Controls -->
    <div class="pen-controls">
        <div class="pen-size">
            <label for="penSize">Pen Size:</label>
            <input type="range" id="penSize" min="1" max="20" value="2">
            <span id="penSizeValue" class="pen-size-value">2</span>
        </div>
    </div>

    <!-- Color Palette -->
    <div class="color-palette">
        <div class="color-option active" style="background-color: #ff0000;" data-color="#ff0000"></div>
        <div class="color-option" style="background-color: #0000ff;" data-color="#0000ff"></div>
        <div class="color-option" style="background-color: #00aa00;" data-color="#00aa00"></div>
        <div class="color-option" style="background-color: #000000;" data-color="#000000"></div>
        <div class="color-option" style="background-color: #ff9900;" data-color="#ff9900"></div>
        <div class="color-option" style="background-color: #9900ff;" data-color="#9900ff"></div>
        <div class="color-option" style="background-color: #ff00ff;" data-color="#ff00ff"></div>
        <div class="color-option" style="background-color: #00ffff;" data-color="#00ffff"></div>
    </div>

    <!-- Content + Overlay Canvas -->
    <div id="container">
        <div id="content">
            {!! $template->html_content !!}
        </div>
        <canvas id="drawCanvas"></canvas>
    </div>

    <div class="btns">
        <button id="clearBtn" class="btn">Clear Drawing</button>
        <button id="downloadPdfBtn" class="btn">Download PDF</button>
        <button id="downloadImgBtn" class="btn" style="background:#28a745;">Download Image</button>
    </div>

    <!-- html2canvas + jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        const container = document.getElementById('container');
        const canvas = document.getElementById('drawCanvas');
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let currentColor = '#ff0000';
        let penSize = 2;

        function resizeCanvas() {
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', handleTouchStart);
        canvas.addEventListener('touchend', handleTouchEnd);
        canvas.addEventListener('touchmove', handleTouchMove);

        function startDrawing(e) {
            drawing = true;
            ctx.beginPath();
            draw(e);
        }

        function stopDrawing() {
            drawing = false;
            ctx.beginPath();
        }

        function draw(e) {
            if (!drawing) return;
            ctx.lineWidth = penSize;
            ctx.lineCap = "round";
            ctx.strokeStyle = currentColor;
            const rect = canvas.getBoundingClientRect();
            let clientX, clientY;
            if (e.type.includes('touch')) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }
            ctx.lineTo(clientX - rect.left, clientY - rect.top);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(clientX - rect.left, clientY - rect.top);
        }

        function handleTouchStart(e) {
            e.preventDefault();
            startDrawing(e);
        }

        function handleTouchEnd(e) {
            e.preventDefault();
            stopDrawing();
        }

        function handleTouchMove(e) {
            e.preventDefault();
            draw(e);
        }

        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                currentColor = this.getAttribute('data-color');
            });
        });

        const penSizeSlider = document.getElementById('penSize');
        const penSizeValue = document.getElementById('penSizeValue');
        penSizeSlider.addEventListener('input', function() {
            penSize = parseInt(this.value);
            penSizeValue.textContent = penSize;
        });

        document.getElementById("clearBtn").addEventListener("click", () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        // 🧩 Optimized PDF download (smaller size)
        document.getElementById("downloadPdfBtn").addEventListener("click", async () => {
            const {
                jsPDF
            } = window.jspdf;
            const mergedCanvas = await html2canvas(container, {
                scale: 1, // Reduce rendering scale for smaller file
                useCORS: true,
                allowTaint: true
            });

            // Use JPEG instead of PNG for smaller size
            const imgData = mergedCanvas.toDataURL("image/jpeg", 0.7); // 70% quality
            const pdf = new jsPDF('p', 'pt', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const imgProps = pdf.getImageProperties(imgData);
            const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(imgData, 'JPEG', 10, 10, pdfWidth - 20, imgHeight - 20);
            pdf.save("{{ $template->name ?? 'template' }}.pdf");
        });

        // 🧩 Compressed Image Download (JPEG)
        document.getElementById("downloadImgBtn").addEventListener("click", async () => {
            const mergedCanvas = await html2canvas(container, {
                scale: 1,
                useCORS: true,
                allowTaint: true
            });

            const smallImg = mergedCanvas.toDataURL("image/jpeg", 0.7);
            const link = document.createElement("a");

            link.href = smallImg;
            link.download = "{{ $template->name ?? 'template' }}.jpg";
            link.click();
        });
    </script>
</body>

</html>