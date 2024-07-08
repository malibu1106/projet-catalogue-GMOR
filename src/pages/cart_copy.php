<?php
// Incluir o arquivo de conexão
require_once '../elements/connect.php';

// Consulta SQL para obter todos os produtos, ordenados por referência
$sql = "SELECT * FROM products ORDER BY ref, color, size";
$stmt = $db->prepare($sql);
$stmt->execute();

$current_ref = '';
$products = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($current_ref != $row['ref']) {
        if (!empty($current_ref)) {
            displayProduct($products);
        }
        $current_ref = $row['ref'];
        $products = [];
    }
    $products[] = $row;
}

// Exibir o último grupo de produtos
if (!empty($products)) {
    displayProduct($products);
}

function displayProduct($products) {
    $main_product = $products[0];
    $colors = array_unique(array_column($products, 'color'));
    $sizes = array_unique(array_column($products, 'size'));

    echo "<div class='product' data-ref='{$main_product['ref']}'>";
    echo "<h2>{$main_product['brand']} - {$main_product['ref']}</h2>";
    echo "<p>Categoria: {$main_product['category']}</p>";
    echo "<p>Preço: €{$main_product['price']}</p>";
    echo "<p>Descrição: {$main_product['content']}</p>";
    echo "<img src='{$main_product['image_1']}' alt='{$main_product['ref']}' width='200'>";
    
    echo "<h3>Cores disponíveis:</h3>";
    echo "<div class='color-options'>";
    foreach ($colors as $color) {
        echo "<button class='color-option' data-color='{$color}'>{$color}</button>";
    }
    echo "</div>";

    echo "<h3>Tamanhos disponíveis:</h3>";
    echo "<div class='size-options'>";
    foreach ($sizes as $size) {
        echo "<button class='size-option' data-size='{$size}'>{$size}</button>";
    }
    echo "</div>";

    echo "<p class='selected-product-info'></p>";
    echo "</div>";
    echo "<hr>";
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        const colorButtons = product.querySelectorAll('.color-option');
        const sizeButtons = product.querySelectorAll('.size-option');
        const infoElement = product.querySelector('.selected-product-info');

        let selectedColor = null;
        let selectedSize = null;

        function updateSelection() {
            if (selectedColor && selectedSize) {
                infoElement.textContent = `Selecionado: Cor ${selectedColor}, Tamanho ${selectedSize}`;
            } else {
                infoElement.textContent = 'Por favor, selecione uma cor e um tamanho.';
            }
        }

        colorButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.classList.contains('selected')) {
                    selectedColor = null;
                    this.classList.remove('selected');
                } else {
                    selectedColor = this.getAttribute('data-color');
                    colorButtons.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                }
                updateSelection();
            });
        });

        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.classList.contains('selected')) {
                    selectedSize = null;
                    this.classList.remove('selected');
                } else {
                    selectedSize = this.getAttribute('data-size');
                    sizeButtons.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                }
                updateSelection();
            });
        });
    });
});
</script>

<style>
.product {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.color-options, .size-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.color-option, .size-option {
    padding: 5px 10px;
    border: 1px solid #ddd;
    background-color: #f8f8f8;
    cursor: pointer;
}

.color-option.selected, .size-option.selected {
    background-color: #e0e0e0;
    border-color: #999;
}

.selected-product-info {
    margin-top: 10px;
    font-weight: bold;
}
</style>