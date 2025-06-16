<div x-data="signupModal()" class="relative">
    <!-- Botão para abrir o modal -->
    <div @click="open = true"
        class="h-full cursor-pointer px-4 py-2 text-white transition">
        Register
    </div>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-sm p-6 relative">
            <button @click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h2 class="text-xl text-[#000000] dark:text-[#fffddd] font-semibold mb-4 text-center">Register</h2>
            <form method="POST" action="{{ url('/api/register') }}" @submit.prevent="register">
                @csrf
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="name">Name:</label>
                    <input type="text" id="name" name="name" x-ref="name" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="email">E-mail</label>
                    <input type="email" id="email" name="email" x-ref="email" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="password">Password</label>
                    <input type="password" id="password" name="password" x-ref="password" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="password_confirmation">Confirm password:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" x-ref="password_confirmation" required class="w-full border rounded px-3 py-2" />
                </div>
                <div x-text="error" class="text-red-600 text-sm mb-2"></div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Registrar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('signupModal', () => ({
        open: false,
        error: '',
        async register(event) {
            this.error = '';
            const name = this.$refs.name.value;
            const email = this.$refs.email.value;
            const password = this.$refs.password.value;
            const password_confirmation = this.$refs.password_confirmation.value;
            try {
                const response = await fetch("{{ url('/api/register') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name, email, password, password_confirmation })
                });
                const data = await response.json();
                if (!response.ok) {
                        console.log(data);
                    if (data.error) {
                    this.error = Object.values(data.error).flat().join(' ');
                    } else {
                        this.error = 'Falha no cadastro';
                    }
                } else {
                    this.closeModal();
                    window.location.reload();
                }
            } catch (e) {
                this.error = 'Erro de conexão';
            }
        },
        closeModal() {
            this.open = false;
            this.error = '';
            this.$refs.name.value = '';
            this.$refs.email.value = '';
            this.$refs.password.value = '';
            this.$refs.password_confirmation.value = '';
        }
    }))
})
</script>