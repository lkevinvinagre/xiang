<div x-data="addaccountModal()" class="relative" x-cloak>
    <!-- Botão para abrir o modal -->
     <span class="flex gap-4 mb-4">
    <p class="text-4xl text-[#000000] dark:text-[#FFFFFF]">Accounts:</p>
    <div @click="open = true"
        class=" rounded bg-green-500 cursor-pointer px-1 py-1 text-white transition text-2xl items-center">
        +
    </div>
    </span>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-sm p-6 relative">
            <button @click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h2 class="text-xl text-[#000000] dark:text-[#fffddd] font-semibold mb-4 text-center">Add</h2>
            <form method="POST" action="{{ url('/api/account/add') }}" @submit.prevent="add">
                @csrf
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="title">Title:</label>
                    <input type="text" id="title" name="title" x-ref="title" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="username">Username:</label>
                    <input type="text" id="username" name="username" x-ref="username" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="url">URL:</label>
                    <input type="url" id="url" name="url" x-ref="url" required class="w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-[#000000] dark:text-[#fffddd] text-sm mb-1" for="password">Password:</label>
                    <input type="password" id="password" name="password" x-ref="password" required class="w-full border rounded px-3 py-2" />
                </div>
                <div x-text="error" class="text-red-600 text-sm mb-2"></div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Add
                </button>
            </form>
        </div>
    </div>
    <div
        x-show="success"
        x-transition
        class="fixed top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50"
        @click="success = false"
        style="display: none;"
    >
        Account was added
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('addaccountModal', () => ({
        open: false,
        error: '',
        success:false,
        async add(event) {
            this.error = '';

            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;

            const title = this.$refs.title.value;
            const username = this.$refs.username.value;
            const url = this.$refs.url.value;
            const password = this.$refs.password.value;
            const response = await fetch("{{ url('/api/account/add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'

                },
                body: JSON.stringify({ title, username, url, password,})
            });
            const data = await response.json();
            if(!response.ok) {
                if(data.error) {
                    this.error = Object.values(data.error)
                    .flat()
                    .join(' ');
                }else {
                    this.error = 'Fails to add';
                }
            } else {
                this.closeModal();
                this.success = true;
                setTimeout(() => { this.success = false }, 3000);
                location.reload();
            }
        },
        closeModal() {
            this.open = false;
            this.error = '';
            this.$refs.title.value = '';
            this.$refs.username.value = '';
            this.$refs.url.value = '';
            this.$refs.password.value = '';
            this.$refs.password_confirmation.value = '';
        }
    }))
})
</script>