<div x-data="logoutBtn()" class="relative">
    <div @click="logout"
        class="h-full cursor-pointer px-4 py-2 bg-red-400 text-white transition">
        Logout
    </div>
</div>
<script>
    document.addEventListener('alpine:init', ()=>{
        Alpine.data('logoutBtn', () => ({
            async logout(event){
                const token = localStorage.getItem('token');
                if (!token) {
                    window.location.href = '/';
                    return;
                }
                try {
                    await fetch("{{ url('/api/auth/logout') }}", {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                        }
                    });
                } catch (e) {

                }
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/';
            }
        }))
    })
</script>