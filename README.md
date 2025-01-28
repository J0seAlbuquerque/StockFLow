# StockFLow
StockFlow é um sistema de gerenciamento de estoque desenvolvido para compor a nota da Materia de Programação Web ministrada pelo Professor Marlon. O sistema permite que usuários gerenciem produtos, vendas e dados da empresa de forma centralizada.

## 🌟 Funcionalidades  
### 1. **Autenticação**  
- Sistema de login e cadastro.  
- Cada conta gerencia uma loja individual.  
- Recuperação de senha e opção de "manter conectado".

### 2. **Gestão de Estoque**  
- Cadastro de produtos com nome, código, fornecedor, categoria, custo, preço de venda, vencimento (opcional) e quantidade.  
- Alertas para produtos com baixa quantidade ou vencimento próximo.  
- Registro de entrada e saída de produtos.  

### 3. **Caixa (Vendas)**  
- Busca de produtos no estoque por nome ou código.  
- Adição de produtos à lista de vendas com cálculo automático de totais.  
- Opções de pagamento: dinheiro, Pix, cartão de crédito/débito (com parcelamento).  
- Geração de comprovantes e ajuste automático do estoque.

### 4. **Histórico de Vendas**  
- Registro de todas as vendas com detalhes como: data, hora, valor, forma de pagamento e lucro bruto.  
- Filtros para visualizar vendas por data, valor ou cliente.  
- Edição ou exclusão de vendas, ajustando o estoque automaticamente.

---

## 🛠️ Tecnologias  
- **Backend:** PHP 8.1  
- **Frontend:** HTML5, CSS3, JavaScript  
- **Banco de Dados:** MySQL  
- **Servidor Local:** XAMPP  
- **Virtualização:** DOCKER

---