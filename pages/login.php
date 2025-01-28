    <?php
        include('../includes/header.php');
    ?>


    <div class="container">
        <div class="box">
            <h1 class="site-name">StockFlow</h1>

            <!-- Seção de Login -->
            <section id="login-section" class="active">
                <form id="login-form" method="POST" action="../process/processa_login.php">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>

                    <div class="form-options">
                        <a href="recuperar_senha.php">Esqueceu a senha?</a>
                    </div>

                    <button type="submit">Entrar</button>
                </form>
                <p class="switch-form">
                    Não tem cadastro? <span id="open-cadastro">Clique aqui</span>
                </p>
            </section>

            <!-- Seção de Cadastro -->
            <section id="cadastro-section">
                <form id="cadastro-form" method="POST" action="../process/processa_cadastro.php" enctype="multipart/form-data">
                    <h2>Cadastro</h2>

                    <label for="name">Nome do Proprietário:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="company_name">Nome da Empresa:</label>
                    <input type="text" id="company_name" name="company_name" required>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="phone">Telefone:</label>
                    <input type="text" id="phone" name="phone" required>

                    <!-- <label for="logo">Logo (opcional):</label>
                    <input type="file" id="logo" name="logo"> -->

                    <button type="submit">Cadastrar</button>
                </form>
                <p class="switch-form">
                    Já tem cadastro? <span id="open-login">Voltar</span>
                </p>
            </section>
        </div>
    </div>
    <script src="../assets/js/_login.js"></script>

    <?php
        include('../includes/footer.php');
    ?>