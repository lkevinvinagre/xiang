<section x-data="accountDashboard()" x-init="fetchAccounts()"  class="w-full flex flex-col items-start p-4">
    <H1 class="p-4 text-6xl bold">Accounts:</H1>
    <table x-show="accounts.length > 0" class="table">
        <tr>
            <td class="p-4">Title:</td>
            <td class="p-4">URL:</td>
            <td class="p-4">Username:</td>
        </tr>
    <tbody>
        <template x-for="account in accounts" :key="account.id">
            <tr>
                <td x-text="account.title" class="p-4"></td>
                <td x-text="account.url" class="p-4"></td>
                <td x-text="account.username" class="p-4"></td>
                <td>
                    <button @click="openModal(account.password)" class="show-pass text-white bg-blue-600 p-2">Show password</button>
                    <button @click="deleteAccount(account.id)" class="text-red-500 p-2">Excluir</button>
                </td>
            </tr>
        </template>
    </tbody>
</table>
<p x-show="accounts.length === 0" class="text-black dark:text-white">Nenhuma conta encontrada.</p>
<div x-show="modalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white dark:bg-gray-900 rounded-lg p-6 w-full max-w-sm">
        <button @click="closeModal" class=" top-2 right-2 text-red-400">&times;</button>
        <h2 x-show="!decryptedPassword" class="text-xl font-semibold text-center">Confirm your Password</h2>
        <h2 x-show="decryptedPassword" class="text-xl font-semibold text-center">This is your Password</h2>
        
        <form x-show="!decryptedPassword" @submit.prevent="decrypt">
            <input type="password" x-model="passwordInput" required class="w-full border rounded px-3 py-2 mb-2" />
            <div class="text-red-600 text-sm mb-2" x-text="error"></div>
            <button type="submit" class="bg-blue-600 text-white w-full py-2 rounded">Confirm</button>
        </form>

        <h3 x-show="decryptedPassword" class="text-lg text-center mt-4" x-text="decryptedPassword"></h3>
    </div>
</div>
</section>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('accountDashboard', () => ({
        accounts: [],
        error: '',
        passwordInput: '',
        selectedAccountPass: null,
        decryptedPassword: '',
        modalOpen: false,
        decrypting: false,

        async fetchAccounts() {
            try {
                const token = localStorage.getItem('token');
                const response = await fetch('/api/account/list', {
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Erro ao buscar contas');
                this.accounts = await response.json();
            } catch {
                this.accounts = [];
            }
        },

        openModal(pass) {
            this.selectedAccountPass = pass;
            this.passwordInput = '';
            this.decryptedPassword = '';
            this.error = '';
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.selectedAccountPass = null;
            this.passwordInput = '';
            this.decryptedPassword = '';
            this.error = '';
        },

        async decrypt() {
            this.decrypting = true;
            const token = localStorage.getItem('token');
            try {
                const response = await fetch('/api/account/decrypt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        password: this.passwordInput,
                        accountpassword: this.selectedAccountPass
                    })
                });
                const data = await response.json();
                if (!response.ok) {
                    this.error = data?.message || 'Failed to decrypt.';
                } else {
                    this.decryptedPassword = data.decrypted;
                }
            } catch (err) {
                this.error = 'Request failed.';
            } finally {
                this.decrypting = false;
            }
        },

        async deleteAccount(id) {
            if (!confirm('Are you sure?')) return;
            const token = localStorage.getItem('token');
            const response = await fetch(`/api/account/destroy?id=${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                this.fetchAccounts();
            } else {
                alert('Error on delete');
            }
        }
    }))
});
</script>