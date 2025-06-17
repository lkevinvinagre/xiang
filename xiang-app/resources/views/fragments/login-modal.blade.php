<!--Login modal blade Fragment-->
<div x-data="loginModal()" class="relative" x-clock>
<!-- Button to go to modal -->
 <div @click="open = true"
    class=" h-full cursor-pointer px-4 py-2 bg-white text-black transition">
    Login
 </div>

 <!-- Modal -->
<div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    x-transition>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-sm p-6 relative">
            <button @click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h2 class="text-xl text-[#000000] dark:text-[#fffddd] font-semibold mb-4 text-center">Login</h2>
            <form method="POST" action="{{ url('/api/auth/login') }}" @submit.prevent="login">
                @csrf
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="email">E-mail</label>
                    <input type="email" id="email" name="email" x-ref="email" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class=" text-[#000000] dark:text-[#fffddd] block text-sm mb-1" for="password">Senha</label>
                    <input type="password" id="password" name="email" x-ref="password" required class="w-full border rounded px-3 py-2" />
                </div>
                <div x-text="error" class="text-red-600 text-sm mb-2"></div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</div>
</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('loginModal', () => ({
        open: false,
        error: '',
        async login(event) {
            this.error = '';
            const email = this.$refs.email.value;
            const password = this.$refs.password.value;
            const response = await fetch("{{ url('/api/auth/login') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email, password })
            });
            const data = await response.json();
            if (!response.ok) {
                if (data.error) {
                    this.error = Object.values(data.error).flat().join(' ');
                } else {
                this.error = 'Fails on register';
            }
            } else {
                //Store token and user info
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));
                this.closeModal();
                window.location.href = '/accounts';
                
            }
        },
        closeModal() {
            this.open = false;
            this.error = '';
            this.$refs.email.value = '';
            this.$refs.password.value = '';
        }
    }))
})
</script>