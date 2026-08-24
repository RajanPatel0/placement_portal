<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Paperless System - Advanced Template Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c7fb8;
            --secondary: #7fcdbb;
            --accent: #edf8b1;
            --dark: #253237;
            --light: #f8f9fa;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--primary);
            color: white;
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        
        .sidebar-header h2 {
            font-size: 1.5rem;
            margin-left: 10px;
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu li.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid var(--accent);
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        .header h1 {
            color: var(--primary);
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .card h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        /* Template Builder */
        .template-builder {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .builder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .tool-btn {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: grab;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            user-select: none;
        }
        
        .tool-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .tool-btn i {
            margin-right: 5px;
        }
        
        .template-canvas {
            min-height: 500px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            background: #fafafa;
            position: relative;
        }
        
        .grid-layout {
            display: grid;
            gap: 15px;
            margin-bottom: 20px;
            min-height: 100px;
        }
        
        .canvas-element {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            position: relative;
            cursor: move;
            transition: all 0.3s;
            user-select: none;
        }
        
        .canvas-element:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .canvas-element.selected {
            border: 2px solid var(--primary);
            box-shadow: 0 0 0 2px rgba(44, 127, 184, 0.2);
        }
        
        .element-controls {
            position: absolute;
            top: -15px;
            right: -15px;
            display: none;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }
        
        .canvas-element.selected .element-controls {
            display: flex;
        }
        
        .control-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            background: white;
            transition: all 0.3s;
        }
        
        .control-btn:hover {
            background: #f0f0f0;
        }
        
        .control-btn.delete:hover {
            background: var(--danger);
            color: white;
        }
        
        .resize-handle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            bottom: -5px;
            right: -5px;
            cursor: nwse-resize;
            display: none;
        }
        
        .canvas-element.selected .resize-handle {
            display: block;
        }
        
        .table-preview {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table-preview th, .table-preview td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .table-preview th {
            background: #f2f2f2;
        }
        
        /* Template Gallery */
        .template-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .template-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .template-card:hover {
            transform: translateY(-5px);
        }
        
        .template-preview {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #eee;
        }
        
        .template-info {
            padding: 15px;
        }
        
        .template-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Grid Controls */
        .grid-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .grid-controls select, .grid-controls input {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
        
        .drop-zone {
            min-height: 50px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        
        .drop-zone.active {
            border-color: var(--primary);
            background-color: rgba(44, 127, 184, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-hospital-alt fa-2x"></i>
                <h2>MediPaperless</h2>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li class="active"><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-file-medical"></i> Templates</a></li>
                    <li><a href="#"><i class="fas fa-prescription"></i> Prescriptions</a></li>
                    <li><a href="#"><i class="fas fa-stethoscope"></i> Progress Notes</a></li>
                    <li><a href="#"><i class="fas fa-heartbeat"></i> Evaluations</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Advanced Template Builder</h1>
                <div class="user-info">
                    <img src="https://i.pravatar.cc/150?img=12" alt="Admin User">
                    <div>
                        <h4>Dr. Sarah Johnson</h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <h3>Templates</h3>
                    <p>Manage and create custom templates</p>
                    <div class="card-count">12</div>
                </div>
                
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <h3>Prescriptions</h3>
                    <p>Digital prescription records</p>
                    <div class="card-count">342</div>
                </div>
                
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3>Progress Notes</h3>
                    <p>Patient progress documentation</p>
                    <div class="card-count">567</div>
                </div>
                
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Evaluations</h3>
                    <p>Medical evaluation forms</p>
                    <div class="card-count">89</div>
                </div>
            </div>
            
            <!-- Template Builder -->
            <div class="template-builder">
                <div class="builder-header">
                    <h2>Drag & Drop Template Builder</h2>
                    <button class="btn btn-primary" id="newTemplateBtn">
                        <i class="fas fa-plus"></i> New Template
                    </button>
                </div>
                
                <div class="grid-controls">
                    <div>
                        <label>Grid Columns:</label>
                        <select id="gridColumns">
                            <option value="1">1 Column</option>
                            <option value="2" selected>2 Columns</option>
                            <option value="3">3 Columns</option>
                            <option value="4">4 Columns</option>
                        </select>
                    </div>
                    <div>
                        <label>Grid Gap:</label>
                        <select id="gridGap">
                            <option value="5">5px</option>
                            <option value="10">10px</option>
                            <option value="15" selected>15px</option>
                            <option value="20">20px</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary" id="addRowBtn">
                        <i class="fas fa-plus"></i> Add Row
                    </button>
                </div>
                
                <div class="toolbar">
                    <div class="tool-btn" data-type="text" draggable="true">
                        <i class="fas fa-font"></i> Text
                    </div>
                    <div class="tool-btn" data-type="table" draggable="true">
                        <i class="fas fa-table"></i> Table
                    </div>
                    <div class="tool-btn" data-type="grid" draggable="true">
                        <i class="fas fa-th"></i> Grid
                    </div>
                    <div class="tool-btn" data-type="icon" draggable="true">
                        <i class="fas fa-icons"></i> Icons
                    </div>
                    <div class="tool-btn" data-type="emoji" draggable="true">
                        <i class="fas fa-smile"></i> Emoji
                    </div>
                    <div class="tool-btn" data-type="blank" draggable="true">
                        <i class="fas fa-square"></i> Blank Space
                    </div>
                    <div class="tool-btn" data-type="divider" draggable="true">
                        <i class="fas fa-grip-lines"></i> Divider
                    </div>
                    <div class="tool-btn" data-type="signature" draggable="true">
                        <i class="fas fa-signature"></i> Signature
                    </div>
                </div>
                
                <div class="template-canvas" id="templateCanvas">
                    <div class="grid-layout" id="gridLayout">
                        <!-- Elements will be added here via drag and drop -->
                    </div>
                </div>
            </div>
            
            <!-- Template Gallery -->
            <h2 style="margin-bottom: 20px;">Template Gallery</h2>
            <div class="template-gallery">
                <div class="template-card">
                    <div class="template-preview">
                        <i class="fas fa-prescription fa-3x" style="color: #2c7fb8;"></i>
                    </div>
                    <div class="template-info">
                        <h3>Standard Prescription</h3>
                        <p>A comprehensive prescription template with medication details and instructions.</p>
                        <div class="template-actions">
                            <button class="btn btn-primary">Use Template</button>
                            <button class="btn btn-secondary">Edit</button>
                        </div>
                    </div>
                </div>
                
                <div class="template-card">
                    <div class="template-preview">
                        <i class="fas fa-stethoscope fa-3x" style="color: #7fcdbb;"></i>
                    </div>
                    <div class="template-info">
                        <h3>Progress Note</h3>
                        <p>Template for documenting patient progress during hospital stay.</p>
                        <div class="template-actions">
                            <button class="btn btn-primary">Use Template</button>
                            <button class="btn btn-secondary">Edit</button>
                        </div>
                    </div>
                </div>
                
                <div class="template-card">
                    <div class="template-preview">
                        <i class="fas fa-heartbeat fa-3x" style="color: #e74c3c;"></i>
                    </div>
                    <div class="template-info">
                        <h3>Cardiology Evaluation</h3>
                        <p>Specialized template for cardiology patient evaluations.</p>
                        <div class="template-actions">
                            <button class="btn btn-primary">Use Template</button>
                            <button class="btn btn-secondary">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Template Modal -->
    <div class="modal" id="newTemplateModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New Template</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="form-group">
                <label for="templateName">Template Name</label>
                <input type="text" id="templateName" class="form-control" placeholder="Enter template name">
            </div>
            <div class="form-group">
                <label for="templateType">Template Type</label>
                <select id="templateType" class="form-control">
                    <option value="prescription">Prescription</option>
                    <option value="progress-note">Progress Note</option>
                    <option value="evaluation">Evaluation</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="templateDescription">Description</label>
                <textarea id="templateDescription" class="form-control" rows="3" placeholder="Enter template description"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-success" style="width: 100%;">Create Template</button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const newTemplateBtn = document.getElementById('newTemplateBtn');
        const newTemplateModal = document.getElementById('newTemplateModal');
        const closeModal = document.querySelector('.close-modal');
        
        newTemplateBtn.addEventListener('click', () => {
            newTemplateModal.style.display = 'flex';
        });
        
        closeModal.addEventListener('click', () => {
            newTemplateModal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === newTemplateModal) {
                newTemplateModal.style.display = 'none';
            }
        });
        
        // Grid controls
        const gridColumns = document.getElementById('gridColumns');
        const gridGap = document.getElementById('gridGap');
        const gridLayout = document.getElementById('gridLayout');
        const addRowBtn = document.getElementById('addRowBtn');
        
        gridColumns.addEventListener('change', updateGridLayout);
        gridGap.addEventListener('change', updateGridLayout);
        
        function updateGridLayout() {
            gridLayout.style.gridTemplateColumns = `repeat(${gridColumns.value}, 1fr)`;
            gridLayout.style.gap = `${gridGap.value}px`;
        }
        
        addRowBtn.addEventListener('click', () => {
            const newRow = document.createElement('div');
            newRow.className = 'drop-zone';
            newRow.innerHTML = '<p>Drop elements here</p>';
            gridLayout.appendChild(newRow);
            setupDropZone(newRow);
        });
        
        // Initialize grid
        updateGridLayout();
        
        // Drag and drop functionality
        let draggedElement = null;
        let currentDropZone = null;
        
        // Setup draggable tool buttons
        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.addEventListener('dragstart', handleDragStart);
            btn.addEventListener('dragend', handleDragEnd);
        });
        
        // Setup drop zones
        function setupDropZone(zone) {
            zone.addEventListener('dragover', handleDragOver);
            zone.addEventListener('dragenter', handleDragEnter);
            zone.addEventListener('dragleave', handleDragLeave);
            zone.addEventListener('drop', handleDrop);
        }
        
        // Initialize existing drop zones
        document.querySelectorAll('.drop-zone').forEach(setupDropZone);
        setupDropZone(gridLayout);
        
        function handleDragStart(e) {
            draggedElement = this;
            this.style.opacity = '0.4';
            e.dataTransfer.setData('text/plain', this.getAttribute('data-type'));
        }
        
        function handleDragEnd() {
            this.style.opacity = '1';
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.classList.remove('active');
            });
        }
        
        function handleDragOver(e) {
            e.preventDefault();
        }
        
        function handleDragEnter(e) {
            e.preventDefault();
            this.classList.add('active');
            currentDropZone = this;
        }
        
        function handleDragLeave() {
            this.classList.remove('active');
        }
        
        function handleDrop(e) {
            e.preventDefault();
            this.classList.remove('active');
            
            const elementType = e.dataTransfer.getData('text/plain');
            createElement(elementType, this);
        }
        
        // Element creation and management
        let elementCount = 0;
        
        function createElement(type, container) {
            elementCount++;
            const newElement = document.createElement('div');
            newElement.className = 'canvas-element';
            newElement.setAttribute('data-id', elementCount);
            newElement.draggable = true;
            
            // Add controls
            const controls = document.createElement('div');
            controls.className = 'element-controls';
            controls.innerHTML = `
                <button class="control-btn edit"><i class="fas fa-edit"></i></button>
                <button class="control-btn delete"><i class="fas fa-trash"></i></button>
            `;
            newElement.appendChild(controls);
            
            // Add resize handle
            const resizeHandle = document.createElement('div');
            resizeHandle.className = 'resize-handle';
            newElement.appendChild(resizeHandle);
            
            // Set content based on type
            switch(type) {
                case 'text':
                    newElement.innerHTML = `
                        <h4>Text Element ${elementCount}</h4>
                        <p>This is a text element. Double-click to edit content.</p>
                    ` + newElement.innerHTML;
                    newElement.style.minHeight = '100px';
                    break;
                    
                case 'table':
                    newElement.innerHTML = `
                        <h4>Table ${elementCount}</h4>
                        <table class="table-preview">
                            <thead>
                                <tr>
                                    <th>Column 1</th>
                                    <th>Column 2</th>
                                    <th>Column 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data 1</td>
                                    <td>Data 2</td>
                                    <td>Data 3</td>
                                </tr>
                                <tr>
                                    <td>Data 4</td>
                                    <td>Data 5</td>
                                    <td>Data 6</td>
                                </tr>
                            </tbody>
                        </table>
                    ` + newElement.innerHTML;
                    break;
                    
                case 'grid':
                    newElement.innerHTML = `
                        <h4>Grid ${elementCount}</h4>
                        <div class="grid-layout" style="grid-template-columns: repeat(2, 1fr); gap: 10px;">
                            <div style="background: #f0f0f0; padding: 10px; border-radius: 5px;">
                                <p>Grid Item 1</p>
                            </div>
                            <div style="background: #f0f0f0; padding: 10px; border-radius: 5px;">
                                <p>Grid Item 2</p>
                            </div>
                        </div>
                    ` + newElement.innerHTML;
                    break;
                    
                case 'icon':
                    newElement.innerHTML = `
                        <h4>Icon Set ${elementCount}</h4>
                        <div style="display: flex; gap: 10px; font-size: 1.5rem;">
                            <i class="fas fa-heartbeat" style="color: #e74c3c;"></i>
                            <i class="fas fa-stethoscope" style="color: #2c7fb8;"></i>
                            <i class="fas fa-prescription" style="color: #7fcdbb;"></i>
                            <i class="fas fa-hospital" style="color: #f39c12;"></i>
                        </div>
                    ` + newElement.innerHTML;
                    break;
                    
                case 'emoji':
                    newElement.innerHTML = `
                        <h4>Emoji Set ${elementCount}</h4>
                        <div style="font-size: 1.5rem;">
                            😊 🏥 💊 ❤️ 🌡️ 🩺 💉
                        </div>
                    ` + newElement.innerHTML;
                    break;
                    
                case 'blank':
                    newElement.innerHTML = `
                        <p>Blank Space ${elementCount}</p>
                    ` + newElement.innerHTML;
                    newElement.style.minHeight = '100px';
                    newElement.style.display = 'flex';
                    newElement.style.alignItems = 'center';
                    newElement.style.justifyContent = 'center';
                    newElement.style.background = '#f9f9f9';
                    break;
                    
                case 'divider':
                    newElement.innerHTML = `
                        <hr style="border: none; border-top: 2px solid #ddd;">
                    ` + newElement.innerHTML;
                    break;
                    
                case 'signature':
                    newElement.innerHTML = `
                        <h4>Signature ${elementCount}</h4>
                        <div style="text-align: center; padding: 20px;">
                            <div style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto 10px;"></div>
                            <p>Signature</p>
                        </div>
                    ` + newElement.innerHTML;
                    break;
            }
            
            // Add to container
            if (container.classList.contains('drop-zone')) {
                container.innerHTML = '';
                container.appendChild(newElement);
            } else {
                container.appendChild(newElement);
            }
            
            // Setup element interactions
            setupElementInteractions(newElement);
        }
        
        function setupElementInteractions(element) {
            // Selection
            element.addEventListener('click', (e) => {
                if (e.target.closest('.control-btn')) return;
                
                document.querySelectorAll('.canvas-element').forEach(el => {
                    el.classList.remove('selected');
                });
                element.classList.add('selected');
            });
            
            // Dragging
            element.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', 'move');
                setTimeout(() => {
                    element.style.opacity = '0.4';
                }, 0);
            });
            
            element.addEventListener('dragend', () => {
                element.style.opacity = '1';
            });
            
            // Delete button
            element.querySelector('.control-btn.delete').addEventListener('click', () => {
                element.remove();
            });
            
            // Edit button
            element.querySelector('.control-btn.edit').addEventListener('click', () => {
                alert('Edit functionality would open a properties panel in a full implementation.');
            });
            
            // Resize functionality
            const resizeHandle = element.querySelector('.resize-handle');
            let isResizing = false;
            
            resizeHandle.addEventListener('mousedown', (e) => {
                isResizing = true;
                e.stopPropagation();
                
                const startX = e.clientX;
                const startY = e.clientY;
                const startWidth = parseInt(document.defaultView.getComputedStyle(element).width, 10);
                const startHeight = parseInt(document.defaultView.getComputedStyle(element).height, 10);
                
                function handleMouseMove(e) {
                    if (!isResizing) return;
                    
                    const newWidth = startWidth + (e.clientX - startX);
                    const newHeight = startHeight + (e.clientY - startY);
                    
                    element.style.width = `${newWidth}px`;
                    element.style.height = `${newHeight}px`;
                }
                
                function handleMouseUp() {
                    isResizing = false;
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                }
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            });
            
            // Double-click to edit text content
            element.addEventListener('dblclick', () => {
                if (element.querySelector('h4')) {
                    const h4 = element.querySelector('h4');
                    const originalText = h4.textContent;
                    
                    h4.innerHTML = `<input type="text" value="${originalText}" style="width: 100%;">`;
                    const input = h4.querySelector('input');
                    input.focus();
                    
                    input.addEventListener('blur', () => {
                        h4.textContent = input.value || originalText;
                    });
                    
                    input.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            h4.textContent = input.value || originalText;
                        }
                    });
                }
            });
        }
        
        // Initialize with some example elements
        document.addEventListener('DOMContentLoaded', () => {
            createElement('text', gridLayout);
            createElement('table', gridLayout);
        });
    </script>
</body>
</html>