<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTU-Placement</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for the search icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <link rel="icon" type="image" href="/public/assets/classic-logo.png">
 <link rel="manifest" href="/public/manifest.json">
    <meta name="theme-color" content="#16a34a">


</head>


<body>
<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/public/sw.js')
      .then(reg => console.log('SW registered'))
      .catch(err => console.log('SW failed', err));
  });
}
</script>


    <!-- This section will trigger the modal -->
    <div class="notification-container">
        @if (session('success'))
        <div class="notification success" id="successNotification">
            <button type="button" class="close-btn" onclick="closeNotification('successNotification')">&times;</button>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if (session('error'))
        <div class="notification error" id="errorNotification">
            <button type="button" class="close-btn" onclick="closeNotification('errorNotification')">&times;</button>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        @if ($errors->any())
        <div class="notification error" id="errorsNotification">
            <button type="button" class="close-btn" onclick="closeNotification('errorsNotification')">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <style>
        .notification-container {
            position: fixed;
            top: 50px;
            right: 20px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .notification {
            max-width: 300px;
            padding: 20px 25px;
            background: linear-gradient(to right, #4caf50, #81c784);
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
            position: relative;
        }

        .notification.error {
            background: linear-gradient(to right, #e53935, #ef5350);
        }

        .notification p,
        .notification ul {
            margin: 0;
        }

        .notification ul {
            padding-left: 20px;
            list-style: none;
            font-size: 0.9rem;
        }

        .close-btn {
            position: absolute;
            top: .5px;
            /* Padding from the top */
            right: .5px;
            /* Padding from the right */
            background: transparent;
            border: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }
    </style>
    <script>
        // Auto-hide the notification after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.notification').forEach(notification => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 1000); // Allow fade-out animation to complete
            });
        }, 10000);

        // Manually close the notification
        function closeNotification(id) {
            const element = document.getElementById(id);
            if (element) {
                element.style.opacity = '0';
                setTimeout(() => {
                    element.style.display = 'none';
                }, 1000); // Allow fade-out animation to complete
            }
        }
    </script>