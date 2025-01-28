-- Descrição das Tabelas:

--     users:           Armazena informações dos usuários (proprietários) e das lojas.
--     company_info:    Complementa as informações da empresa, como CNPJ e endereço.
--     products:        Gerencia os produtos do estoque, incluindo preço de custo e venda, fornecedor e vencimento.
--     sales:           Registra as vendas realizadas, com o valor total, método de pagamento e lucro.
--     sales_items:     Detalha os itens vendidos em cada venda, vinculados à tabela de vendas.
--     stock_movements: Registra entradas e saídas de produtos no estoque.

-- Criando o banco de dados
CREATE DATABASE stockflow;

-- Usando o banco de dados
USE stockflow;

-- Tabela de usuários (proprietários/lojas)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    company_name VARCHAR(100) NOT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de informações da empresa
CREATE TABLE company_info (
    company_id INT PRIMARY KEY,
    cnpj VARCHAR(20) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL,
    number VARCHAR(10) NOT NULL,
    neighborhood VARCHAR(100) NOT NULL,
    state VARCHAR(2) NOT NULL,
    city VARCHAR(100) NOT NULL,
    FOREIGN KEY (company_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tabela de produtos no estoque
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    supplier VARCHAR(100),
    category VARCHAR(100),
    cost_price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2) NOT NULL,
    expiration_date DATE DEFAULT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tabela de vendas
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_name VARCHAR(100) DEFAULT NULL,
    payment_method ENUM('cash', 'pix', 'credit_card', 'debit_card') NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    profit DECIMAL(10, 2) NOT NULL,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tabela de itens vendidos
CREATE TABLE sales_items (
    sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(sale_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Tabela de entrada/saída de produtos
CREATE TABLE stock_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('entry', 'exit') NOT NULL,
    quantity INT NOT NULL,
    movement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
