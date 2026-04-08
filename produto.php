<?php
session_start();
include_once("objetos/ProdutosController.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$controller = new ProdutosController();
$produto = $controller->localizarProduto($_GET['id']);

if (!$produto) {
    header("Location: index.php");
    exit();
}

// Cálculo do carrinho para a navbar
$total_cart_items = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $qtd) {
        $total_cart_items += $qtd;
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($produto->nome) ?> - Loja Senac</title>
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
            --dark: #0f172a;
            --light: #f8fafc;
            --text-gray: #64748b;
            --border-color: #e2e8f0;
        }
        
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }

        .product-view-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }

        @media (max-width: 800px) {
            .product-view-container { grid-template-columns: 1fr; gap: 30px; }
        }

        .product-gallery {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }

        .product-gallery img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        
        .product-gallery img:hover { transform: scale(1.02); }

        .product-info {
            padding: 20px 0;
        }

        .product-path {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 15px;
            font-weight: 500;
        }

        .product-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .product-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 25px;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .product-price small {
            font-size: 1rem;
            color: var(--text-gray);
            font-weight: 400;
        }

        .product-short-desc {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(37, 99, 235, 0.03);
            border-left: 4px solid var(--primary);
            border-radius: 0 12px 12px 0;
        }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f1f5f9;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-gray);
            margin-bottom: 30px;
        }

        .action-area {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-main-buy {
            flex: 2;
            background: var(--success);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25);
        }

        .btn-main-buy:hover {
            background: var(--success-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
        }

        .btn-main-cart {
            flex: 1;
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .btn-main-cart:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.2);
        }

        .benefits {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .benefit-item i {
            font-size: 1.2rem;
            color: var(--success);
        }

        /* Nav Reuse */
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
        }
        .brand-logo { font-size: 1.5rem; font-weight: 800; color: var(--dark); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .brand-logo i { color: var(--primary); }
        .nav-right { display: flex; gap: 20px; align-items: center; }
        .cart-icon { position: relative; color: var(--dark); font-size: 1.2rem; text-decoration: none; }
        .cart-badge { position: absolute; top: -8px; right: -10px; background: #ef4444; color: white; font-size: 0.7rem; font-weight: bold; padding: 2px 6px; border-radius: 10px; }
        .admin-link { background: #f1f5f9; color: var(--dark); padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
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
            <span class="cart-badge"><?= $total_cart_items ?></span>
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

<div class="product-view-container">
    <div class="product-gallery">
        <?php if(!empty($produto->imagem)): ?>
            <img src="uploads/<?= htmlspecialchars($produto->imagem); ?>" alt="<?= htmlspecialchars($produto->nome); ?>">
        <?php else: ?>
            <div style="font-size: 5rem; color: #cbd5e1;"><i class="fa-solid fa-image"></i></div>
        <?php endif; ?>
    </div>

    <div class="product-info">
        <div class="product-path">Loja Senac > Coleção Atual > <?= htmlspecialchars($produto->nome); ?></div>
        <h1 class="product-name"><?= htmlspecialchars($produto->nome); ?></h1>
        
        <div class="stock-status">
            <?php if($produto->quantidade > 0): ?>
                <i class="fa-solid fa-check-circle" style="color: var(--success);"></i> Em estoque (<?= $produto->quantidade ?> disponíveis)
            <?php else: ?>
                <i class="fa-solid fa-times-circle" style="color: var(--danger);"></i> Fora de estoque
            <?php endif; ?>
        </div>

        <div class="product-price">
            <small>por apenas</small>
            R$ <?= number_format((float)$produto->preco, 2, ',', '.'); ?>
        </div>

        <div class="product-short-desc">
            <?= nl2br(htmlspecialchars($produto->descricao)); ?>
        </div>

        <div class="action-area">
            <?php if($produto->quantidade > 0): ?>
                <a href="carrinho.php?acao=add&id=<?= $produto->id; ?>" class="btn-main-buy">
                    <i class="fa-solid fa-bolt"></i> COMPRAR AGORA
                </a>
                <a href="carrinho.php?acao=add&id=<?= $produto->id; ?>" class="btn-main-cart" title="Adicionar ao Carrinho">
                    <i class="fa-solid fa-cart-plus"></i>
                </a>
            <?php else: ?>
                <button class="btn-main-buy" style="background: #cbd5e1; cursor: not-allowed; box-shadow: none;" disabled>
                    <i class="fa-solid fa-ban"></i> PRODUTO INDISPONÍVEL
                </button>
            <?php endif; ?>
        </div>

        <div class="benefits">
            <div class="benefit-item">
                <i class="fa-solid fa-truck-fast"></i>
                <span>Entrega rápida em todo o Brasil</span>
            </div>
            <div class="benefit-item">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Compra 100% segura</span>
            </div>
            <div class="benefit-item">
                <i class="fa-solid fa-arrow-rotate-left"></i>
                <span>Devolução grátis em 7 dias</span>
            </div>
            <div class="benefit-item">
                <i class="fa-solid fa-credit-card"></i>
                <span>Em até 12x sem juros</span>
            </div>
        </div>
    </div>
</div>

<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px; text-align: center;">
    <a href="index.php" style="color: var(--text-gray); text-decoration: none; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Voltar para a vitrine de produtos
    </a>
</div>

</body>
</html>
