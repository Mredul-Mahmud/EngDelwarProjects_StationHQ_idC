  <style>
        /* body { background: #222; color: #fff; font-family: Arial, sans-serif; }
        .header { background: #1a3d1a; padding: 20px 0; text-align: center; border-bottom: 4px solid #3c6e3c; }
        .header h1 { color: #fff; margin: 0; font-size: 2.2rem; letter-spacing: 2px; }
        .header .subtitle { color: #b2ffb2; font-size: 1.1rem; } */
        
        /* Custom Navigation Bar Styles */
        .custom-navbar {
            background: #1a3d1a;
            border-bottom: 2px solid #4a7c4a;
            padding: 0;
            font-style: italic;
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .nav-item {
            position: relative;
            border-right: 1px solid #000;
            /* border-radius: 30px; */
        }
        
        .nav-item:last-child {
            border-right: none;
        }
        
        .nav-link {
            color: #ffff00 !important;
            font-weight: bold;
            padding: 12px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            background: linear-gradient(180deg, #2e4d2e 0%, #1a3d1a 100%);
            border: none;
            transition: all 0.3s ease;
            min-width: 140px;
            justify-content: center;
            border-radius: 2px;
            margin: 0 2px;
         
        }
        
        .nav-link:hover {
            background: linear-gradient(180deg, #3c6e3c 0%, #b0d8b0 100%);
            color: #1aff00 !important;
        }
        
        /* Active/Home button styling */
        .nav-link.active {
            background: linear-gradient(180deg, #dac350 0%, #40391e 100%);
            color: #000 !important;
            position: relative;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            /* background: #ff0000; */
        }
        
        /* Dropdown indicators */
        .dropdown-toggle::after {
            content: '▼';
            margin-left: 8px;
            color: #ffff00;
            font-size: 10px;
            border: none
        }
        
        .nav-link.active .dropdown-toggle::after {
            color: #000;
        }
        
        /* Dropdown menu styling */
        .dropdown-menu {
            background: #1a3d1a;
            border: 1px solid #4a7c4a;
            border-radius: 0;
            margin-top: 0;
        }
        
        .dropdown-item {
            color: #ffff00;
            padding: 8px 20px;
        }
        
        .dropdown-item:hover {
            background: #2e4d2e;
            color: #ffff00;
        }
        
        /* Bottom border line */
        .nav-bottom-border {
            height: 2px;
            background: #4a7c4a;
            width: 100%;
        }
        .custom-navbar .dropdown:hover .dropdown-menu {
    display: block;
    margin-top: 0; /* ঝাঁকুনি বন্ধ করার জন্য */
}
        
        /* .left { background: #2e4d2e; border-radius: 8px; text-align: center; padding: 18px; margin-bottom: 20px; }
        .left img { width: 100px; border-radius: 8px; border: 2px solid #ffd700; }
        .left h3 { margin-top: 12px; color: #ffd700; font-size: 1.1rem; }
        .center { background: #333; border-radius: 8px; padding: 22px; margin-bottom: 20px; }
        .center h2 { color: #ffd700; font-size: 1.3rem; margin-bottom: 10px; }
        .grid .box { background: #2e4d2e; border-radius: 8px; text-align: center; border: 1px solid #3c6e3c; padding: 18px; margin-bottom: 18px; }
        .grid .box h4 { color: #ffd700; margin-bottom: 10px; font-size: 1.1rem; }
        .activities { background: #222; border-radius: 8px; border: 1px solid #3c6e3c; padding: 22px; margin-top: 36px; }
        .activities h3 { color: #ffd700; font-size: 1.2rem; margin-bottom: 16px; }
        .activities .caption { color: #fff; margin-top: 8px; text-align: center; font-size: 0.98rem; } */
    </style>


 <nav class="custom-navbar">
        <div class="nav-container">
            <div class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </div>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Apply For Sticker
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                </ul>
            </div>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Apply For ID Card
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                </ul>
            </div>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Graveyard
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                </ul>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#">Photo Gallery</a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#">Staff Org</a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#">History</a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#">Others</a>
            </div>
        </div>
        <div class="nav-bottom-border"></div>
    </nav>
    