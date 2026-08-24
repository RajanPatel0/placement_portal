 <header class="header">
     <div class="logo">
         <!-- <div class="logo-icon"><i class="fas fa-briefcase"></i></div> -->
         <img src="/public/assets/classic-logo.png" alt="PTU Placement Logo" class="logo-image">
         <span style="font-size:20px; margin-left: -10px; font-family: font-family: sans-serif; ">PTU Placement</span>
     </div>
<div id="installAppSection" style="display:none;">
  <button id="installAppBtn">⬇ Install App</button>
</div>

<script>
let deferredPrompt; // must declare globally

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();          // prevent default Chrome prompt
  deferredPrompt = e;          // save for later

  document.getElementById('installAppSection').style.display = 'block';
});

document.getElementById('installAppBtn').addEventListener('click', async () => {
  if (!deferredPrompt) {
    alert('Install not available. Use Chrome on HTTPS.');
    return;
  }

  deferredPrompt.prompt();      // show Chrome install prompt
  const choice = await deferredPrompt.userChoice;
  console.log('User choice:', choice.outcome);

  deferredPrompt = null;
  document.getElementById('installAppSection').style.display = 'none';
});

// Hide button after successful install
window.addEventListener('appinstalled', () => {
  deferredPrompt = null;
  document.getElementById('installAppSection').style.display = 'none';
});
</script>
     @auth
         <div class="header-actions" style="position: relative;">
             <a href="{{ route('notifications') }}">
                 <!-- Notification Bell Icon with Animation -->
                 <div style="position: relative; display: flex; align-items: center;">
                     <i class="fas fa-bell"
                         style="cursor: pointer; font-size: 1.5rem; color: #fff; transition: color 0.2s;">
                     </i>
                     @php
                         $unreadCount = DB::table('drive_notifications')
                             ->where('user_id', auth()->id())
                             ->whereNull('read_at')
                             ->count();
                     @endphp
                     @if ($unreadCount > 0)
                         <span class="notification-badge">{{ $unreadCount }}</span>
                     @endif
                 </div>
             </a>
         </div>
     @endauth


     <style>
         .logo {
             display: flex;
             align-items: center;
             gap: 12px;
             font-weight: 700;
             font-size: 1.5rem;
         }

         .logo-image {
             width: 40px;
             /* Adjust as needed */
             height: 40px;
             /* Adjust as needed */
             object-fit: contain;
             /* Ensures image maintains aspect ratio */
             border-radius: 6px;
             /* Optional: adds rounded corners */
         }



         @keyframes ring {
             0% {
                 transform: rotate(0);
             }

             10% {
                 transform: rotate(15deg);
             }

             20% {
                 transform: rotate(-10deg);
             }

             30% {
                 transform: rotate(7deg);
             }

             40% {
                 transform: rotate(-5deg);
             }

             50% {
                 transform: rotate(3deg);
             }

             60% {
                 transform: rotate(-2deg);
             }

             70% {
                 transform: rotate(1deg);
             }

             80% {
                 transform: rotate(-1deg);
             }

             90% {
                 transform: rotate(0);
             }

             100% {
                 transform: rotate(0);
             }
         }

         .notification-badge {
             position: absolute;
             top: -7px;
             right: -10px;
             background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
             color: #fff;
             border-radius: 50%;
             padding: 0px 5px;
             font-size: 0.6rem;
             font-weight: bold;
             box-shadow: 0 2px 8px rgba(247, 37, 133, 0.2);
             border: 2px solid #fff;
             animation: badge-pop 0.4s;
         }

         @keyframes badge-pop {
             0% {
                 transform: scale(0);
             }

             80% {
                 transform: scale(1.2);
             }

             100% {
                 transform: scale(1);
             }
         }
     </style>
 </header>

 <style>
     /* Header Styles with Gradient */
     .header {
         background: linear-gradient(135deg, #677feaff 0%, #5f43a0ff 100%);
         color: white;
         position: sticky;
         top: 0;
         padding: 8px 11px;
         display: flex;
         justify-content: space-between;
         position: sticky;
         z-index: 100;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
     }

     .logo {
         display: flex;
         align-items: center;
         gap: 12px;
         font-weight: 700;
         font-size: 1.5rem;
     }

     .logo-icon {
         font-size: 2rem;
         background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
         -webkit-background-clip: text;
         -webkit-text-fill-color: transparent;
     }

     .header-actions {
         display: flex;
         gap: 18px;
         align-items: center;
         padding-right: 10px;
     }

     .header-actions i {
         font-size: 1.3rem;
         transition: transform 0.2s;
     }

     .header-actions i:hover {
         transform: scale(1.1);
     }

     span {
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

     }
 </style>
