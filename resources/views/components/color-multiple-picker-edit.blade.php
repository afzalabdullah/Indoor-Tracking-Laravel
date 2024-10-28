<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown input[type="text"] {
        width: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .dropdown-menu {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        max-height: 150px;
        overflow-y: auto;
        width: 100%;
        z-index: 1;
    }

    .dropdown-menu-item {
        padding: 10px;
        cursor: pointer;
    }

    .selected-items {
        margin-top: 10px;
    }

    .selected-item {
        display: inline-block;
        margin-right: 5px;
        margin-bottom: 5px;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #e0e0e0;
    }

    .close-button {
        cursor: pointer;
        margin-left: 5px;
    }
</style>
<div class="dropdown">
    <input type="text" placeholder="Search colors..." id="colorInput">
    <div class="dropdown-menu" id="colorDropdown">
    </div>
</div>

<div class="selected-items" id="selectedItems">
    {{-- @dd($leathers->leatherColors[0]->color) --}}
    @foreach ($leathers->leatherColors as $leathercolor)
        <div class="selected-item">
            {{ $leathercolor->color }}
            <span class="close-button">x</span>
        </div>
    @endforeach
</div>
<input type="hidden" name="color" id="colorArray">
<script>
    const colors = [
        "Black", "Brown", "Antique Brown", "Blue", "Pink", "Orange", "Dark Green",
        "Grey", "Brown Jungle", "White", "Yellow", "Red", "Royal Blue", "Tan",
        "Off-White", "Maroon", "Purple", "Brown Rub Buff", "Dark Brown", "Dark Blue"
    ];

    const colorInput = document.getElementById('colorInput');
    const colorDropdown = document.getElementById('colorDropdown');
    const selectedItems = document.getElementById('selectedItems');
    let colorArrayInput = document.getElementById('colorArray');
    let selectedColors = {!!  json_encode($leathers->leatherColors->pluck('color')->toArray()) !!};

    function populateDropdown(query) {
        colorDropdown.innerHTML = '';
        const filteredColors = colors.filter(color => color.toLowerCase().includes(query.toLowerCase()));
        filteredColors.forEach(color => {
            const option = document.createElement('div');
            option.classList.add('dropdown-menu-item');
            option.textContent = color;
            option.addEventListener('click', () => {
                addSelectedColor(color);
                colorInput.value = '';
                colorDropdown.style.display = 'none';
            });
            colorDropdown.appendChild(option);
        });
        colorDropdown.style.display = 'block';
    }

    function addSelectedColor(color) {
        if (!selectedColors.includes(color)) {
            selectedColors.push(color);
            renderSelectedItems();
            updateColorArrayInput();
        }
    }

    function removeSelectedColor(index) {
        selectedColors.splice(index, 1);
        renderSelectedItems();
        updateColorArrayInput();
    }

    function renderSelectedItems() {
        selectedItems.innerHTML = '';
        selectedColors.forEach((color, index) => {
            const item = document.createElement('div');
            item.classList.add('selected-item');
            item.textContent = color;
            const closeButton = document.createElement('span');
            closeButton.textContent = 'x';
            closeButton.classList.add('close-button');
            closeButton.addEventListener('click', () => removeSelectedColor(index));
            item.appendChild(closeButton);
            selectedItems.appendChild(item);
        });
    }

    function updateColorArrayInput() {
        colorArrayInput.value = selectedColors.join('|');
    }

    colorInput.addEventListener('input', () => {
        populateDropdown(colorInput.value);
    });

    renderSelectedItems();
</script>
