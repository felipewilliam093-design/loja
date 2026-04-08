<?php
session_start();
include_once("objetos/ProdutosController.php");

$controller = new ProdutosController();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Logic for modifying cart
if (isset($_GET['acao']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $acao = $_GET['acao'];

    $produtoDb = $controller->localizarProduto($id);

    if ($produtoDb) {
        if ($acao == 'add') {
            $current_qty = isset($_SESSION['carrinho'][$id]) ? $_SESSION['carrinho'][$id] : 0;
            if ($current_qty + 1 <= $produtoDb->quantidade) {
                $_SESSION['carrinho'][$id] = $current_qty + 1;
            }
        } else if ($acao == 'remover') {
            if (isset($_SESSION['carrinho'][$id])) {
                unset($_SESSION['carrinho'][$id]);
            }
        } else if ($acao == 'mais') {
            $current_qty = isset($_SESSION['carrinho'][$id]) ? $_SESSION['carrinho'][$id] : 0;
            if ($current_qty + 1 <= $produtoDb->quantidade) {
                $_SESSION['carrinho'][$id] = $current_qty + 1;
            }
        } else if ($acao == 'menos') {
            if (isset($_SESSION['carrinho'][$id])) {
                $_SESSION['carrinho'][$id] -= 1;
                if ($_SESSION['carrinho'][$id] <= 0) {
                    unset($_SESSION['carrinho'][$id]);
                }
            }
        }
    }

    // Redirect to clear URL and avoid duplicate submission on refresh
    header("Location: carrinho.php");
    exit();
}

$cart_items = [];
$subtotal = 0;
$total_items_count = 0;

foreach ($_SESSION['carrinho'] as $id => $quantidade) {
    try {
        $produto = $controller->localizarProduto($id);
        if ($produto) {
            $item = (object)[
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => $quantidade,
                'imagem' => $produto->imagem,
                'estoque' => $produto->quantidade
            ];
            $cart_items[] = $item;
            $subtotal += $item->preco * $quantidade;
            $total_items_count += $quantidade;
        } else {
            // Remove from cart if product no longer exists
            unset($_SESSION['carrinho'][$id]);
        }
    } catch (Exception $e) {
    }
}

$frete = $subtotal > 0 ? 15.00 : 0.00;
$total = $subtotal + $frete;
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meu Carrinho - Loja Senac</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reusing Modern Storefront variables */
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
        
        /* Dynamic Header for Storefront */
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
        
        .nav-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .cart-icon {
            position: relative;
            color: var(--primary); /* highlight icon for current page */
            font-size: 1.2rem;
            text-decoration: none;
            transition: color 0.2s;
            margin-right: 15px;
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

        /* Cart Specific Styles */
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: start;
        }
        
        @media (max-width: 900px) {
            .cart-container {
                grid-template-columns: 1fr;
            }
        }
        
        .cart-main {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 30px;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light);
        }
        
        .cart-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .item-count {
            color: var(--text-gray);
            font-size: 1rem;
            font-weight: 500;
        }
        
        /* Cart Item */
        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
            gap: 20px;
        }
        .cart-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .item-img {
            width: 100px;
            height: 100px;
            background: var(--light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #cbd5e1;
            overflow: hidden;
            flex-shrink: 0;
        }
        .item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex-grow: 1;
        }
        
        .item-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }
        
        .item-id {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 15px;
        }
        
        .item-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Quantity Control */
        .qty-controls {
            display: flex;
            align-items: center;
            background: var(--light);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        .btn-qty {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            color: var(--dark);
            transition: background 0.2s;
        }
        .btn-qty:hover { background: #e2e8f0; }
        .qty-input {
            width: 40px;
            border: none;
            text-align: center;
            font-weight: 600;
            background: transparent;
            font-size: 1rem;
            outline: none;
        }
        
        /* Prices */
        .item-price {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--dark);
        }
        
        .btn-remove {
            background: none;
            border: none;
            color: var(--text-gray);
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.2s;
            padding: 8px;
        }
        .btn-remove:hover { color: var(--danger); }
        
        /* Cart Summary Sidebar */
        .cart-summary {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 30px;
            position: sticky;
            top: 100px;
        }
        
        .cart-summary h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
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
        }
        
        .btn-checkout:hover {
            background: var(--success-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }
        
        .btn-keep-shopping {
            display: block;
            text-align: center;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: text-decoration 0.2s;
        }
        .btn-keep-shopping:hover {
            text-decoration: underline;
        }
        
        /* Empty States */
        .empty-cart-msg {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-cart-msg i {
            font-size: 4rem;
            color: var(--light);
            background: #f1f5f9;
            padding: 30px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .empty-cart-msg h2 {
            color: var(--dark);
            margin-bottom: 10px;
        }
        .empty-cart-msg p {
            color: var(--text-gray);
            margin-bottom: 25px;
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
            <a href="cadastro-cliente.php" class="admin-link" style="background: transparent; color: var(--primary); border: 1px solid var(--primary);"><i class="fa-solid fa-user-plus"></i> Cadastrar</a>
        <?php endif; ?>
        
        <div style="width: 1px; height: 24px; background-color: #cbd5e1; margin: 0 5px;"></div>
        
        <?php if(isset($_SESSION["funcionario_id"])): ?>
            <a href="admin-produtos.php" class="admin-link" style="background: #f1f5f9; color: var(--dark); border: 1px solid #e2e8f0;" title="Painel Admin"><i class="fa-solid fa-table-columns"></i></a>
        <?php else: ?>
            <a href="login.php" class="admin-link" style="background: #f1f5f9; color: var(--text-gray); border: 1px solid #e2e8f0;" title="Acesso Restrito"><i class="fa-solid fa-lock"></i></a>
        <?php endif; ?>
    </div>
</nav>

<div class="cart-container">
    <!-- Left Column: Cart Items -->
    <div class="cart-main">
        <div class="cart-header">
            <h1><i class="fa-solid fa-cart-shopping" style="color: var(--primary);"></i> Meu Carrinho</h1>
            <span class="item-count"><?= $total_items_count ?> itens</span>
        </div>
        
        <?php if(!empty($cart_items)) : ?>
            <?php foreach($cart_items as $item) : ?>
            <div class="cart-item">
                <div class="item-img">
                    <?php if(!empty($item->imagem)): ?>
                        <img src="uploads/<?= htmlspecialchars($item->imagem); ?>" alt="Produto">
                    <?php else: ?>
                        <i class="fa-solid fa-image"></i>
                    <?php endif; ?>
                </div>
                
                <div class="item-details">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h3 class="item-title"><?= htmlspecialchars($item->nome); ?></h3>
                        <div class="item-price">R$ <?= number_format((float)($item->preco * $item->quantidade), 2, ',', '.'); ?></div>
                    </div>
                    <div class="item-id">Ref: #<?= $item->id; ?> | Valor unitário: R$ <?= number_format((float)$item->preco, 2, ',', '.'); ?></div>
                    
                    <div class="item-actions">
                        <div class="qty-controls">
                            <a href="carrinho.php?acao=menos&id=<?= $item->id; ?>" class="btn-qty" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;"><i class="fa-solid fa-minus"></i></a>
                            <input type="text" class="qty-input" value="<?= $item->quantidade; ?>" readonly>
                            <?php if($item->quantidade < $item->estoque): ?>
                                <a href="carrinho.php?acao=mais&id=<?= $item->id; ?>" class="btn-qty" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;"><i class="fa-solid fa-plus"></i></a>
                            <?php else: ?>
                                <span class="btn-qty" style="display:inline-flex; align-items:center; justify-content:center; color: #cbd5e1; cursor: not-allowed;" title="Estoque máximo atingido"><i class="fa-solid fa-plus"></i></span>
                            <?php endif; ?>
                        </div>
                        <a href="carrinho.php?acao=remover&id=<?= $item->id; ?>" class="btn-remove" title="Remover do carrinho" style="text-decoration:none;">
                            <i class="fa-solid fa-trash-can"></i> Remover
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="empty-cart-msg">
                <i class="fa-solid fa-cart-arrow-down"></i>
                <h2>Seu carrinho está vazio</h2>
                <p>Navegue pela nossa loja e adicione produtos incríveis ao seu carrinho!</p>
                <a href="index.php" class="admin-link" style="background: var(--primary); color: white; padding: 12px 24px;">Voltar para as compras</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Column: Cart Summary -->
    <?php if(!empty($cart_items)) : ?>
    <div class="cart-summary">
        <h2>Resumo da Compra</h2>
        
        <div class="summary-row">
            <span>Subtotal (<?= $total_items_count ?> itens)</span>
            <span>R$ <?= number_format((float)$subtotal, 2, ',', '.'); ?></span>
        </div>
        <div class="summary-row">
            <span>Frete Estimado</span>
            <span>R$ <?= number_format((float)$frete, 2, ',', '.'); ?></span>
        </div>
        
        <div class="summary-total">
            <span>Total</span>
            <span style="color: var(--success);">R$ <?= number_format((float)$total, 2, ',', '.'); ?></span>
        </div>
        
        <?php if(isset($_SESSION['cliente_id'])): ?>
            <a href="checkout.php" class="btn-checkout" style="text-decoration:none;">
                Finalizar Compra <i class="fa-solid fa-arrow-right"></i>
            </a>
        <?php else: ?>
            <a href="login-cliente.php" class="btn-checkout" style="text-decoration:none;">
                Identificar-se para Comprar <i class="fa-solid fa-right-to-bracket"></i>
            </a>
        <?php endif; ?>
        
        <a href="index.php" class="btn-keep-shopping">
            <i class="fa-solid fa-arrow-left" style="margin-right: 5px;"></i> Continuar Comprando
        </a>
    </div>
    <?php endif; ?>
</div>

</script>

</body>
</html>
