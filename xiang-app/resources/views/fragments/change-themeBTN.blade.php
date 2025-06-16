
<div class="bg-[#666666ff] w-full flex items-center justify-between row">
        <span class="ml-4">
            <label for="theme-toggle" class="flex items-center gap-1 cursor-pointer px-1 py-1">
                <span class="text-sm">🌞</span>
                <input id="theme-toggle" type="range" min="1" max="2" value="1" class="slider" style="accent-color: #2563eb;">
                <span class="text-sm">🌙</span>
            </label>
        </span>
        <span>
            <p class="text-white p-2 mr-4">Powered by Xiang</p>
        </span>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('theme-toggle');
    // Aplica o tema salvo ao carregar
    if(localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        slider.value = 2;
    } else {
        document.documentElement.classList.remove('dark');
        slider.value = 1;
    }

    slider.addEventListener('input', function() {
        if(this.value == 2) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });
});
</script>