<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Rajua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d1117;
        }

        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 30px rgba(99, 102, 241, 0.2);
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            transform: translateY(-24px) scale(0.85);
            color: #818cf8;
        }

        .btn-glow {
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
        }
        
        .btn-glow:hover {
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.8), 0 0 40px rgba(168, 85, 247, 0.6);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative">

    <!-- Live Canvas Background -->
    <canvas id="bg-canvas"></canvas>

    <!-- Form Login Card -->
    <div class="relative z-10 w-full max-w-md p-8 mx-4 rounded-2xl login-card">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-600/20 text-indigo-400 mb-3 border border-indigo-500/30">
                <i class="fa-solid fa-store text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-wide">Toko Rajua</h2>
            <p class="text-gray-400 text-sm mt-1">Sistem Informasi Kasir & Cetak Struk</p>
        </div>

        <!-- Session Status / Errors (Laravel Flash Messages) -->
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-400 bg-green-900/30 p-3 rounded-lg border border-green-500/30">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm font-medium text-red-400 bg-red-900/30 p-3 rounded-lg border border-red-500/30">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email / Username Input -->
            <div class="relative input-group">
                <input type="text" id="email" name="email" value="{{ old('email') }}" required placeholder=" " 
                    class="w-full px-4 py-3 bg-gray-900/60 text-white rounded-xl border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all duration-200">
                <label for="email" class="absolute left-4 top-3 text-gray-400 pointer-events-none transition-all duration-200 origin-left">
                    <i class="fa-regular fa-envelope mr-2"></i>Email / Username
                </label>
            </div>

            <!-- Password Input -->
            <div class="relative input-group">
                <input type="password" id="password" name="password" required placeholder=" " 
                    class="w-full px-4 py-3 bg-gray-900/60 text-white rounded-xl border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all duration-200">
                <label for="password" class="absolute left-4 top-3 text-gray-400 pointer-events-none transition-all duration-200 origin-left">
                    <i class="fa-solid fa-lock mr-2"></i>Password
                </label>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-400 hover:text-gray-300 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2">Ingat Saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl btn-glow focus:outline-none">
                Masuk Sistem
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Toko Rajua. All rights reserved.
        </div>
    </div>

    <!-- Live Background Script -->
    <script>
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');

        let width, height;
        let particles = [];
        const particleCount = 75;
        const maxDistance = 140;

        let mouse = {
            x: null,
            y: null,
            radius: 180
        };

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.x;
            mouse.y = e.y;
        });

        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.8;
                this.vy = (Math.random() - 0.5) * 0.8;
                this.radius = Math.random() * 2 + 1;
                this.baseAlpha = Math.random() * 0.5 + 0.3;
                this.color = Math.random() > 0.3 ? '#818cf8' : '#c084fc';
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;

                if (mouse.x !== null && mouse.y !== null) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < mouse.radius) {
                        let force = (mouse.radius - dist) / mouse.radius;
                        this.x -= (dx / dist) * force * 3;
                        this.y -= (dy / dist) * force * 3;
                    }
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.baseAlpha;
                ctx.fill();
            }
        }

        function init() {
            resize();
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        function connectParticles() {
            for (let a = 0; a < particles.length; a++) {
                for (let b = a + 1; b < particles.length; b++) {
                    let dx = particles[a].x - particles[b].x;
                    let dy = particles[a].y - particles[b].y;
                    let dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDistance) {
                        let opacity = 1 - (dist / maxDistance);
                        ctx.beginPath();
                        ctx.moveTo(particles[a].x, particles[a].y);
                        ctx.lineTo(particles[b].x, particles[b].y);
                        ctx.strokeStyle = '#6366f1';
                        ctx.globalAlpha = opacity * 0.25;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            particles.forEach(p => {
                p.update();
                p.draw();
            });

            connectParticles();
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => {
            resize();
        });

        init();
        animate();
    </script>
</body>
</html>