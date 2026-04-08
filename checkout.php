<?php
session_start();
include_once("objetos/ProdutosController.php");

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit();
}

$controller = new ProdutosController();
$subtotal = 0;
$total_items_count = 0;

foreach ($_SESSION['carrinho'] as $id => $quantidade) {
    try {
        $produto = $controller->localizarProduto($id);
        if ($produto) {
            $subtotal += $produto->preco * $quantidade;
            $total_items_count += $quantidade;
        }
    } catch (Exception $e) {}
}

$frete = $subtotal > 0 ? 15.00 : 0.00;
$total = $subtotal + $frete;

// Se o form foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processamento da compra (mock)
    $_SESSION['carrinho'] = []; // Esvazia o carrinho
    echo "<script>alert('Compra realizada com sucesso!'); window.location.href='index.php';</script>";
    exit();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Loja Senac</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #10b981;
            --success-hover: #059669;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --dark: #0f172a;
            --light: #f8fafc;
            --text-gray: #64748b;
            --border-color: #e2e8f0;
        }
        
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }

        /* Navigation Header */
        .store-nav {
            padding: 20px 5%;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            font-family: 'Inter', sans-serif;
        }
        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-logo i { color: var(--primary); }
        .nav-right { display: flex; gap: 20px; align-items: center; }
        .cart-icon {
            position: relative;
            color: var(--primary);
            font-size: 1.2rem;
            text-decoration: none;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
        }
        .admin-link {
            background: #f1f5f9;
            color: var(--dark);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .admin-link:hover { background: #e2e8f0; }

        /* General Layout */
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: start;
        }
        @media (max-width: 900px) {
            .checkout-container { grid-template-columns: 1fr; }
        }
        .checkout-main, .checkout-summary {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .checkout-summary {
            position: sticky;
            top: 100px;
        }
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .input-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-gray);
        }
        .input-group input, .input-group select {
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            transition: all 0.2s;
        }
        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: white;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 10px;
        }
        .payment-option {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .payment-option input[type="radio"] { display: none; }
        .payment-option i { font-size: 1.5rem; margin-bottom: 10px; display: block; color: var(--text-gray); }
        .payment-option span { font-size: 0.9rem; font-weight: 600; color: var(--text-gray); }
        
        /* Selected State */
        .payment-option:has(input[type="radio"]:checked) {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        .payment-option:has(input[type="radio"]:checked) span {
            color: var(--primary);
        }

        /* Summary Blocks */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: var(--text-gray);
            font-size: 1.05rem;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed var(--border-color);
            color: var(--dark);
            font-size: 1.4rem;
            font-weight: 800;
        }

        .btn-checkout {
            width: 100%;
            background: var(--success);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 30px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            text-decoration: none;
        }
        .btn-checkout:hover {
            background: var(--success-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        /* Support Buttons */
        .btn-secondary, .btn-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            padding: 14px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 10px;
            font-size: 0.95rem;
        }

        .btn-outline {
            color: var(--text-gray);
            border: 2px solid var(--border-color);
            background: transparent;
        }
        .btn-outline:hover {
            border-color: var(--text-gray);
            color: var(--dark);
            background: #f8fafc;
        }

        .btn-secondary {
            color: var(--primary);
            background: rgba(37,99,235,0.05);
            border: 2px solid transparent;
        }
        .btn-secondary:hover {
            background: rgba(37,99,235,0.1);
        }

    </style>
</head>
<body>

<nav class="store-nav">
    <a href="index.php" class="brand-logo">
        <i class="fa-solid fa-store"></i> Loja Senac
    </a>
    <div class="nav-right" style="display: flex; align-items: center; gap: 15px;">
        <a href="carrinho.php" class="cart-icon" style="margin-right: 10px;">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-badge"><?= $total_items_count ?></span>
        </a>
        
        <?php if(isset($_SESSION["cliente_id"])): ?>
            <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <span style="font-weight: 600; font-size: 0.85rem; color: var(--dark);">
                    <i class="fa-solid fa-circle-user" style="color: var(--primary);"></i> Olá, <?= htmlspecialchars(explode(' ', trim($_SESSION["cliente_nome"]))[0]) ?>
                </span>
                <a href="logout-cliente.php" title="Sair" style="color: #ef4444; font-size: 0.9rem; text-decoration: none;"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        <?php else: ?>
            <a href="login-cliente.php" class="admin-link" style="background: var(--primary); color: white; border: 1px solid var(--primary);"><i class="fa-solid fa-user"></i> Entrar</a>
        <?php endif; ?>
        
        <div style="width: 1px; height: 24px; background-color: #cbd5e1; margin: 0 5px;"></div>
        
        <?php if(isset($_SESSION["funcionario_id"])): ?>
            <a href="admin-produtos.php" class="admin-link" style="background: #f1f5f9; color: var(--dark); border: 1px solid #e2e8f0;" title="Painel Admin"><i class="fa-solid fa-table-columns"></i></a>
        <?php else: ?>
            <a href="login.php" class="admin-link" style="background: #f1f5f9; color: var(--text-gray); border: 1px solid #e2e8f0;" title="Acesso Restrito"><i class="fa-solid fa-lock"></i></a>
        <?php endif; ?>
    </div>
</nav>

<div class="checkout-container">
    <div class="checkout-main">
        <form id="checkout-form" method="POST" action="checkout.php">
            <h2 class="section-title"><i class="fa-solid fa-map-location-dot"></i> Entrega</h2>
            <div class="form-grid">
                <div class="input-group full-width">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" required placeholder="Ex: João Silva">
                </div>
                <div class="input-group">
                    <label>CEP</label>
                    <input type="text" id="cep" name="cep" required placeholder="00000-000">
                </div>
                <div class="input-group">
                    <label>Bairro</label>
                    <input type="text" id="bairro" name="bairro" required placeholder="Centro">
                </div>
                <div class="input-group full-width">
                    <label>Logradouro / Rua</label>
                    <input type="text" id="logradouro" name="endereco" required placeholder="Ex: Avenida Principal">
                </div>
                <div class="input-group">
                    <label>Número</label>
                    <input type="text" id="numero" name="numero" required placeholder="Ex: 100">
                </div>
                <div class="input-group">
                    <label>Cidade / UF</label>
                    <input type="text" id="cidade" name="cidade" required placeholder="Cidade - UF" readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                </div>
                <div class="input-group full-width">
                    <label>Complemento (opcional)</label>
                    <input type="text" name="complemento" placeholder="Apto 45">
                </div>
            </div>
            
            <h2 class="section-title" style="margin-top:40px;"><i class="fa-solid fa-credit-card"></i> Pagamento</h2>
            <div class="payment-methods">
                <label class="payment-option">
                    <input type="radio" name="pagamento" value="pix" required checked>
                    <i class="fa-brands fa-pix"></i>
                    <span>PIX</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="pagamento" value="cartao">
                    <i class="fa-regular fa-credit-card"></i>
                    <span>Cartão</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="pagamento" value="boleto">
                    <i class="fa-solid fa-barcode"></i>
                    <span>Boleto</span>
                </label>
            </div>
        </form>
    </div>
    
    <div class="checkout-summary">
        <h2 class="section-title"><i class="fa-solid fa-bag-shopping"></i> Resumo (<?= $total_items_count ?> itens)</h2>
        
        <div class="summary-row">
            <span>Subtotal</span>
            <span>R$ <?= number_format((float)$subtotal, 2, ',', '.'); ?></span>
        </div>
        <div class="summary-row">
            <span>Frete Especial</span>
            <span>R$ <?= number_format((float)$frete, 2, ',', '.'); ?></span>
        </div>
        
        <div class="summary-total">
            <span>Total Final</span>
            <span style="color: var(--success);">R$ <?= number_format((float)$total, 2, ',', '.'); ?></span>
        </div>
        
        <button type="submit" form="checkout-form" class="btn-checkout">
            Concluir Pagamento <i class="fa-solid fa-check"></i>
        </button>
        
        <div style="text-align: center; margin-top:20px; font-size: 0.85rem; color: var(--text-gray); margin-bottom: 25px;">
            <i class="fa-solid fa-shield-halved" style="margin-right: 5px;"></i> Checkout Seguro e Criptografado
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="carrinho.php" class="btn-outline">
                <i class="fa-solid fa-cart-shopping"></i> Editar Meu Carrinho
            </a>
            <a href="index.php" class="btn-secondary">
                <i class="fa-solid fa-store"></i> Continuar Comprando na Loja
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('cep').addEventListener('blur', function() {
        let cep = this.value.replace(/\D/g, '');
        
        if (cep !== "") {
            let validacep = /^[0-9]{8}$/;

            if(validacep.test(cep)) {
                // Preenche enquanto busca
                document.getElementById('logradouro').value = "...";
                document.getElementById('bairro').value = "...";
                document.getElementById('cidade').value = "...";

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!("erro" in data)) {
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade + " - " + data.uf;
                            document.getElementById('numero').focus();
                        } else {
                            alert("CEP não encontrado.");
                            clearForm();
                        }
                    })
                    .catch(() => {
                        alert("Erro ao buscar o CEP.");
                        clearForm();
                    });
            } else {
                alert("Formato de CEP inválido.");
                clearForm();
            }
        }
    });

    function clearForm() {
        document.getElementById('logradouro').value = "";
        document.getElementById('bairro').value = "";
        document.getElementById('cidade').value = "";
    }
</script>

</body>
</html>
