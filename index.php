<?php
session_start();
include_once "objetos/ProdutosController.php";

$controller = new ProdutosController();
$produtos = $controller->index();

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
    <title>Loja Senac - Compre Roupas e Produtos</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Modern Storefront Styles */
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #10b981;
            --success-hover: #059669;
            --dark: #0f172a;
            --light: #f8fafc;
            --text-gray: #64748b;
        }
        
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--dark), #1e293b);
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: -40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.2rem;
            color: #cbd5e1;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Product Grid */
        .store-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 10;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            border: 1px solid rgba(0,0,0,0.02);
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .product-img {
            height: 220px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #cbd5e1;
            position: relative;
            overflow: hidden;
        }
        
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img img {
            transform: scale(1.05);
        }
        
        .badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }
        
        .product-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .product-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .product-desc {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 20px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
        }
        
        .price {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
        }
        
        .stock {
            font-size: 0.85rem;
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 12px;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
        }
        
        .btn-buy {
            flex: 2;
            background: var(--success);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        
        .btn-buy:hover {
            background: var(--success-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }
        
        .btn-cart {
            flex: 1;
            background: var(--light);
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-cart:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        
        /* Empty State */
        .empty-store {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-top: 40px;
        }
        .empty-store i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
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
            color: var(--dark);
            font-size: 1.2rem;
            text-decoration: none;
            transition: color 0.2s;
            margin-right: 15px;
        }
        .cart-icon:hover { color: var(--primary); }
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

<div class="hero">
    <h1>Nova Coleção de Temporada</h1>
    <p>Descubra os melhores produtos com qualidade excepcional e design moderno separados especialmente para você.</p>
</div>

<div class="store-container">
    <?php if($produtos && count($produtos) > 0) : ?>
    <div class="product-grid">
        <?php foreach($produtos as $produto) : ?>
        <div class="product-card" style="position: relative; display: flex; flex-direction: column;">
            <!-- Botão Ver Detalhes no canto superior direito -->
            <a href="produto.php?id=<?= $produto->id ?>" title="Ver detalhes" style="position: absolute; top: 12px; right: 12px; z-index: 20; background: rgba(255,255,255,0.9); color: var(--primary); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; transition: all 0.2s;">
                <i class="fa-solid fa-eye" style="font-size: 0.85rem;"></i>
            </a>

            <?php if(isset($produto->novo)): ?>
                <span class="badge">Novo</span>
            <?php endif; ?>
            
            <div class="product-img">
                <?php if(!empty($produto->imagem)): ?>
                    <img src="uploads/<?= htmlspecialchars($produto->imagem); ?>" alt="Imagem do produto">
                <?php else: ?>
                    <i class="fa-solid fa-image"></i>
                <?php endif; ?>
            </div>
            
            <div class="product-content" style="flex-grow: 1; display: flex; flex-direction: column;">
                <h3 class="product-title"><?= htmlspecialchars($produto->nome); ?></h3>
                <p class="product-desc" style="flex-grow: 1;"><?= htmlspecialchars($produto->descricao); ?></p>
                
                <div class="product-meta">
                    <div class="price">R$ <?= number_format((float)$produto->preco, 2, ',', '.'); ?></div>
                    <div class="stock">
                        <i class="fa-solid fa-boxes-stacked"></i> <?= $produto->quantidade; ?>
                    </div>
                </div>
                
                <div class="action-buttons" style="margin-top: auto;">
                    <?php if($produto->quantidade > 0): ?>
                        <a href="carrinho.php?acao=add&id=<?= $produto->id; ?>" class="btn-buy" style="text-decoration:none; flex: 2; font-size: 0.9rem;">
                            <i class="fa-solid fa-bolt"></i> Comprar
                        </a>
                        <a href="carrinho.php?acao=add&id=<?= $produto->id; ?>" class="btn-cart" title="Adicionar ao Carrinho" style="flex: 1;">
                            <i class="fa-solid fa-cart-plus"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn-buy" style="background-color: #cbd5e1; cursor: not-allowed; box-shadow: none; flex: 1;" disabled>
                            <i class="fa-solid fa-ban"></i> Esgotado
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-store">
        <i class="fa-solid fa-box-open"></i>
        <h2>Nenhum produto disponível</h2>
        <p style="color: var(--text-gray); margin-top: 10px;">Estamos atualizando nosso estoque. Volte novamente em breve!</p>
    </div>
    <?php endif; ?>
</div>



</body>
</html>