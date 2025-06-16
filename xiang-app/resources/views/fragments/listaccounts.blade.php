
<section class="w-full flex flex-col items-start p-4">
    @include('fragments.addaccount-modal')
     <table class="table" id="accounts-table" style="display:none;">
        <thead>
            <tr>
                <th class="text-[#000000] dark:text-[#FFFFFF]">Nome</th>
                <th class="text-[#000000] dark:text-[#FFFFFF]">Usuário</th>
                <th class="text-[#000000] dark:text-[#FFFFFF]">Ações</th>
            </tr>
        </thead>
        <tbody>
            <!--Dynamic Content -->
        </tbody>
        <p id="no-accounts" style="display:none;">Nenhuma conta encontrada.</p>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('token');

    fetch('/api/account/list', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erro ao buscar contas');
        return response.json();
    })
    .then(accounts => {
        const table = document.getElementById('accounts-table');
        const tbody = table.querySelector('tbody');
        const noAccounts = document.getElementById('no-accounts');
        tbody.innerHTML = '';

        if (Array.isArray(accounts) && accounts.length > 0) {
            accounts.forEach(account => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-[#000000] dark:text-[#FFFFFF]">${account.name || ''}</td>
                    <td class="text-[#000000] dark:text-[#FFFFFF]">${account.username || ''}</td>
                    <td class="text-[#000000] dark:text-[#FFFFFF]">
                        <a href="/accounts/${account.id}">Ver</a>
                        <a href="/accounts/${account.id}/edit">Editar</a>
                        <button onclick="deleteAccount(${account.id})">Excluir</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            table.style.display = '';
            noAccounts.style.display = 'none';
        } else {
            table.style.display = 'none';
            noAccounts.style.display = '';
        }
    })
    .catch(() => {
        document.getElementById('accounts-table').style.display = 'none';
        document.getElementById('no-accounts').style.display = '';
    });
});
function deleteAccount(id) {
    if (!confirm('Tem certeza?')) return;
    const token = localStorage.getItem('token');
    fetch(`/api/account/destroy?id=${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) location.reload();
        else alert('Erro ao excluir');
    });
}
</script>