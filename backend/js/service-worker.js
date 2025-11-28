// NaviGo Service Worker
// Version 1.0.0 - Full offline caching strategy

const CACHE_NAME = 'navigo-v1.0.0';
const OFFLINE_URL = '/NaviGo0.0.2/Index.php';

// Files to cache for offline functionality
const CACHE_FILES = [
  // Main pages
  '/NaviGo0.0.2/Index.php',
  '/NaviGo0.0.2/Login/user_login.php',
  '/NaviGo0.0.2/Login/user_register.php',
  '/NaviGo0.0.2/Login/business_login.php',
  '/NaviGo0.0.2/User/user_dashboard.php',
  
  // CSS files
  '/NaviGo0.0.2/css/Index.css',
  '/NaviGo0.0.2/css/user_login.css',
  '/NaviGo0.0.2/css/user_dashboard.css',
  '/NaviGo0.0.2/css/business_login.css',
  '/NaviGo0.0.2/css/user_register.css',
  
  // JavaScript files
  '/NaviGo0.0.2/js/dark-mode.js',
  '/NaviGo0.0.2/js/mobile-features.js',
  
  // Images and assets
  '/NaviGo0.0.2/Assets/Images/NaviGo_Logo.png',
  '/NaviGo0.0.2/Assets/Images/icons/icon-192x192.png',
  '/NaviGo0.0.2/Assets/Images/icons/icon-512x512.png',
  
  // Manifest
  '/NaviGo0.0.2/js/manifest.json'
];

// Install event - cache all essential files
self.addEventListener('install', event => {
  console.log('Service Worker: Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Service Worker: Caching files');
        return cache.addAll(CACHE_FILES);
      })
      .then(() => {
        console.log('Service Worker: Installation complete');
        return self.skipWaiting();
      })
      .catch(error => {
        console.error('Service Worker: Installation failed', error);
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  console.log('Service Worker: Activating...');
  
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== CACHE_NAME) {
              console.log('Service Worker: Deleting old cache', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('Service Worker: Activation complete');
        return self.clients.claim();
      })
  );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip chrome-extension and other non-http requests
  if (!event.request.url.startsWith('http')) {
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        // Return cached version if available
        if (cachedResponse) {
          console.log('Service Worker: Serving from cache', event.request.url);
          return cachedResponse;
        }

        // Otherwise fetch from network
        return fetch(event.request)
          .then(response => {
            // Check if response is valid
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response for caching
            const responseToCache = response.clone();

            // Cache the response for future use
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });

            return response;
          })
          .catch(error => {
            console.log('Service Worker: Network request failed', error);
            
            // If it's a navigation request and we're offline, show offline page
            if (event.request.mode === 'navigate') {
              return caches.match(OFFLINE_URL);
            }
            
            // For other requests, return a custom offline response
            if (event.request.destination === 'image') {
              return new Response(
                '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#f0f0f0"/><text x="100" y="100" text-anchor="middle" fill="#999">Image unavailable offline</text></svg>',
                { headers: { 'Content-Type': 'image/svg+xml' } }
              );
            }
            
            if (event.request.destination === 'style') {
              return new Response('/* Styles unavailable offline */', {
                headers: { 'Content-Type': 'text/css' }
              });
            }
            
            if (event.request.destination === 'script') {
              return new Response('// Script unavailable offline', {
                headers: { 'Content-Type': 'application/javascript' }
              });
            }
            
            // Generic offline response
            return new Response('Content unavailable offline', {
              status: 503,
              statusText: 'Service Unavailable'
            });
          });
      })
  );
});

// Background sync for when connection is restored
self.addEventListener('sync', event => {
  console.log('Service Worker: Background sync triggered');
  
  if (event.tag === 'background-sync') {
    event.waitUntil(
      // Perform any background sync tasks here
      console.log('Service Worker: Performing background sync')
    );
  }
});

// Push notifications (for future implementation)
self.addEventListener('push', event => {
  console.log('Service Worker: Push notification received');
  
  const options = {
    body: event.data ? event.data.text() : 'New update available!',
    icon: '/NaviGo0.0.2/Assets/Images/icons/icon-192x192.png',
    badge: '/NaviGo0.0.2/Assets/Images/icons/icon-72x72.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Open NaviGo',
        icon: '/NaviGo0.0.2/Assets/Images/icons/icon-96x96.png'
      },
      {
        action: 'close',
        title: 'Close',
        icon: '/NaviGo0.0.2/Assets/Images/icons/icon-96x96.png'
      }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('NaviGo', options)
  );
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
  console.log('Service Worker: Notification clicked');
  
  event.notification.close();

  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/NaviGo0.0.2/Index.php')
    );
  }
});

// Message handling for communication with main thread
self.addEventListener('message', event => {
  console.log('Service Worker: Message received', event.data);
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'GET_VERSION') {
    event.ports[0].postMessage({ version: CACHE_NAME });
  }
});
