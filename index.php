<?php

/**
 * Página principal - ONG Amigos de Rua
 * Exibe animais disponíveis para adoção vindos do banco de dados
 */

require_once __DIR__ . '/includes/database.php';


// Busca animais disponíveis para adoção (exibe até 12 animais)
$animais = getAnimaisDisponiveis(12);

// Função auxiliar para exibir badge do sexo
function getBadgeSexo($sexo)
{
  return strtoupper($sexo);
}

function getIdadeAnimal(array $animal)
{
  $anos = isset($animal['idade_anos']) ? (int) $animal['idade_anos'] : 0;
  $meses = isset($animal['idade_meses']) ? (int) $animal['idade_meses'] : 0;

  if ($anos <= 0 && $meses <= 0) {
    return 'Idade não informada';
  }

  if ($anos > 0 && $meses > 0) {
    return $anos . 'a ' . $meses . 'm';
  }

  if ($anos > 0) {
    return $anos . ' ano' . ($anos > 1 ? 's' : '');
  }

  return $meses . ' mês' . ($meses > 1 ? 'es' : '');
}

// Função para gerar o caminho da foto
function getFotoPath($foto)
{
  if (!empty($foto) && file_exists(__DIR__ . '/admin/uploads/' . $foto)) {
    return './admin/uploads/' . sanitize($foto);
  }
  // Imagem padrão se não houver foto
  return './img/placeholder-animal.svg';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <title>ONG Amigos de Rua</title>
</head>

<body>
  <header class="header">
    <section>
      <a href="#" class="logo">
        <img src="./img/logo-ong 1.png" alt="logo" />
      </a>

      <!-- Botão Hamburger -->
      <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav class="navbar" id="navbar">
        <button class="menu-close" id="menuClose" aria-label="Fechar menu">&times;</button>
        <a href="#inicio">Início</a>
        <a href="#about">Nossa ONG</a>
        <a href="formularios/quero-adotar.php">Quero Adotar</a>
        <a href="formularios/lar-temporario.php">Lar temporário</a>
        <a href="https://www.instagram.com/ong_brecho/">Brechó da ONG</a>
        <a href="#contato">Contato</a>
        <a href="#" class="botao">Faça sua Doação</a>
      </nav>
    </section>
  </header>

  <div class="home-container">
    <section id="inicio">
      <div class="content">
        <h3>
          Nosso propósito <br />
          são os animais
        </h3>
        <a href="#" class="botao">Saiba como ajudar</a>
      </div>
    </section>
  </div>

  <section class="faixa-roxa">
    <div class="infos">
      <div class="info-item">
        <h3>+4k</h3>
        <p>Castrações gratuitas</p>
      </div>

      <div class="info-item">
        <h3>+200</h3>
        <p>Adoções responsáveis</p>
      </div>

      <div class="info-item">
        <h3>+7k</h3>
        <p>Procedimentos de consulta e internação solidária</p>
      </div>
    </div>
  </section>

  <section class="about" id="about">
    <h3 class="title">Conheça a ONG amigos de rua</h3>
    <div class="row">
      <div class="container-img">
        <img src="./img/happy-dog-smiling-portrait 1.png" alt="doghappy" />
      </div>
      <div class="content">
        <p>
          Um grupo de civis criou a Sociedade Civil Amigos de Rua (ONG) para
          defender os direitos dos animais diante do abandono e dos
          maus-tratos na cidade. A organização atua promovendo conscientização
          sobre maus-tratos, campanhas de castração e ações junto ao poder
          público para garantir políticas de proteção animal. Além de cuidar
          dos animais, a ONG também busca melhorar a saúde e segurança da
          população, especialmente em áreas com altos índices de abandono e
          acidentes envolvendo animais.
        </p>
      </div>
    </div>
  </section>

  <section class="adocao" id="adotar">
    <h2 class="title">Animais para Adoção</h2>

    <div class="container bloco-animais">
      <div class="box-container">
        <?php if (empty($animais)): ?>
          <div class="adocao-vazio">
            <p>
              No momento não temos animais disponíveis para adoção.<br>
              Em breve novos amigos estarão esperando por você!
            </p>
          </div>
        <?php else: ?>
          <?php foreach ($animais as $animal): ?>
            <div class="box">
              <div class="animal-media">
                <img src="<?php echo getFotoPath($animal['foto'] ?? ''); ?>"
                  alt="<?php echo sanitize($animal['nome'] ?? 'Animal'); ?>"
                  onerror="this.src='./img/placeholder-animal.svg'" />
                <span class="animal-status">Disponível</span>
              </div>

              <div class="animal-conteudo">
                <h3><?php echo sanitize(strtoupper($animal['nome'] ?? 'Sem nome')); ?></h3>

                <p class="animal-linha">
                  <?php echo ucfirst(sanitize($animal['tipo'] ?? 'não informado')); ?> • <?php echo getIdadeAnimal($animal); ?>
                </p>

                <div class="animal-meta">
                  <span class="animal-chip">Sexo: <?php echo getBadgeSexo($animal['sexo'] ?? '-'); ?></span>
                  <span class="animal-chip"><?php echo ucfirst(sanitize($animal['porte'] ?? 'não informado')); ?></span>
                </div>

                <?php if (!empty($animal['descricao'])): ?>
                  <p class="descricao-animal">
                    <?php
                    $descricao = trim((string) $animal['descricao']);
                    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                      $descricaoCurta = mb_strlen($descricao) > 140 ? mb_substr($descricao, 0, 140) . '...' : $descricao;
                    } else {
                      $descricaoCurta = strlen($descricao) > 140 ? substr($descricao, 0, 140) . '...' : $descricao;
                    }
                    echo sanitize($descricaoCurta);
                    ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($animais)): ?>
      <div style="text-align: center; margin-top: 30px;">
        <a href="formularios/quero-adotar.php" class="botao">
          ❤️ Quero Adotar um Animal
        </a>
      </div>
    <?php endif; ?>
  </section>

  <section class="localizacao" id="contato">
    <div class="mapa-container">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1188.0779857917107!2d-50.572494212343884!3d-29.45888046971541!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9518dbc3a85f6585%3A0xafdcd616de54e9c!2sS%C3%A3o%20Francisco%20de%20Paula%2C%20RS%2C%2095400-000!5e0!3m2!1spt-BR!2sbr!4v1764074072888!5m2!1spt-BR!2sbr"
        width="600"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
    <div class="contato-info">
      <div class="info-block">
        <h3>ENDEREÇO</h3>
        <p class="info-line">
          <span class="icon icon-pin" aria-hidden="true">
            <!-- simple pin SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path
                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z" />
            </svg>
          </span>
          <span class="info-text">São Fransisco de Paula - Rio Grande do Sul</span>
        </p>
      </div>

      <div class="info-block">
        <h3>TELEFONE</h3>
        <p class="info-line">
          <span class="icon icon-phone" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path
                d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57.12.33.05.71-.24 1.01l-2.21 2.21z" />
            </svg>
          </span>
          <span class="info-text">(51) 99262-0157</span>
        </p>
      </div>

      <div class="info-block">
        <h3>EMAIL</h3>
        <p class="info-line">
          <span class="icon icon-mail" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path
                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
            </svg>
          </span>
          <span class="info-text"><a href="mailto:ongamigosderua@gmail.com">ongamigosderua@gmail.com</a></span>
        </p>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="footer-top-line"></div>
    <div class="footer-content">
      <div class="footer-column menu-column">
        <h3>Menu</h3>
        <ul>
          <li><a href="#inicio">Início</a></li>
          <li><a href="#about">Nossa ONG</a></li>
          <li><a href="#adotar">Quero Adotar</a></li>
          <li><a href="#lar">Lar Temporário</a></li>
          <li><a href="#brecho">Brechó da ONG</a></li>
          <li><a href="#contato">Contato</a></li>
        </ul>
      </div>

      <div class="footer-column contato-column">
        <h3>Contato</h3>
        <ul>
          <li><span class="icon">✉️</span> amigosderuaong@gmail.com</li>
          <li><span class="icon">📇</span> CNPJ: 13.045852/0001-47</li>
        </ul>
      </div>

      <div class="footer-column redes-column">
        <h3>Redes Sociais</h3>
        <div class="social-icons">
          <!-- SVG icons kept inline for crisp rendering -->
          <a href="#" aria-label="WhatsApp" class="si">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
            </svg>
          </a>
          <a href="#" aria-label="Facebook" class="si">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
          </a>
          <a href="#" aria-label="LinkedIn" class="si">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
            </svg>
          </a>
          <a href="#" aria-label="YouTube" class="si">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
            </svg>
          </a>
          <a href="#" aria-label="Email" class="si">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
            </svg>
          </a>
        </div>
      </div>

      <!-- Instagram blocks: left spans columns 1-3, right spans 3-5 to match mockup spacing -->
      <div class="instagram-block instagram-left">
        <div class="instagram-header">
          <svg class="instagram-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
          </svg>
          <h3>INSTAGRAM DA ONG</h3>
        </div>
        <p class="instagram-desc">
          Nosso propósito são os animais. Nos acompanhe no instagram.
        </p>
        <p class="instagram-handle">@ong_amigosderua</p>
      </div>

      <div class="instagram-block instagram-right">
        <div class="instagram-header">
          <svg class="instagram-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
          </svg>
          <h3>INSTAGRAM DO BRECHÓ DA ONG</h3>
        </div>
        <p class="instagram-desc">
          Nosso brechó é uma ação onde arrecadamos valores para ajudar nos
          resgates e também na ração dos animais que ficam em lar temporário.
        </p>
        <p class="instagram-handle">@ong_brecho</p>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="fb-left"></div>
      <p class="copyright">© 2025 - ONG Amigos de Rua</p>
      <div class="footer-credits">
        <p>Design por: xxxxxxxxx</p>
        <p>Desenvolvido por: xxxxx</p>
      </div>
    </div>
  </footer>

  <script>
    // Menu lateral mobile
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');
    const navbar = document.getElementById('navbar');
    const header = document.querySelector('.header');
    const navLinks = navbar ? navbar.querySelectorAll('a') : [];

    const syncHeaderOffset = () => {
      if (!header) {
        return;
      }

      const headerHeight = Math.ceil(header.getBoundingClientRect().height);
      document.documentElement.style.setProperty('--header-offset', `${headerHeight}px`);
    };

    // Abrir menu
    const openMenu = () => {
      if (!navbar || !menuToggle) {
        return;
      }

      navbar.classList.add('active');
      menuToggle.classList.add('active');
      document.body.style.overflow = 'hidden'; // Previne scroll da página
    };

    // Fechar menu
    const closeMenu = () => {
      if (!navbar || !menuToggle) {
        return;
      }

      navbar.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.style.overflow = ''; // Restaura scroll
    };

    if (menuToggle) {
      menuToggle.addEventListener('click', openMenu);
    }

    if (menuClose) {
      menuClose.addEventListener('click', closeMenu);
    }

    // Fechar menu ao clicar em um link
    navLinks.forEach((link) => {
      link.addEventListener('click', closeMenu);
    });

    // Fechar menu ao clicar no backdrop
    if (navbar) {
      navbar.addEventListener('click', (e) => {
        if (e.target === navbar) {
          closeMenu();
        }
      });
    }

    // Fechar menu com tecla ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navbar && navbar.classList.contains('active')) {
        closeMenu();
      }
    });

    window.addEventListener('load', syncHeaderOffset);
    window.addEventListener('resize', () => {
      syncHeaderOffset();
      if (window.innerWidth > 768) {
        closeMenu();
      }
    });

    syncHeaderOffset();
  </script>

  <!-- Carrossel de animais (mobile) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script>
    $(function() {
      var BREAKPOINT = 768;
      var $slider = $('.adocao .box-container');
      var resizeTimer;

      function initCarousel() {
        var isMobile = window.innerWidth <= BREAKPOINT;
        var temSlides = $slider.find('.box').length > 0;

        if (isMobile && temSlides && !$slider.hasClass('slick-initialized')) {
          $slider.slick({
            dots: true,
            arrows: false,
            infinite: false,
            speed: 380,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            swipe: true,
            touchThreshold: 8,
          });
        } else if (!isMobile && $slider.hasClass('slick-initialized')) {
          $slider.slick('unslick');
        }
      }

      initCarousel();

      $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(initCarousel, 150);
      });
    });
  </script>
</body>

</html>