<!DOCTYPE html>
<html>
<head>
  <title>CoopinCoffe</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
  <link rel="stylesheet" href="css/index.css">
  
</head>
<body>

<nav class="navbar-scroll">
  <div class="navbar-logo">
    <a href="#home">CoopinCoffe</a>
  </div>
  
  <div class="menu-icon" onclick="toggleMenu()">⋮</div>

  <div class="dropdown-menu" id="dropdownMenu">
    <a href="cadastro/cadastro.php">📝 Registro</a>
    <a href="login.php">🔐 Login</a>
  </div>
</nav>


<header class="bgimg w3-display-container" id="home">
  <div class="w3-display-middle w3-center">
    <span class="w3-text-white" style="font-size:90px"><img src="img/bannertext.png" alt="Banner"></span>
  </div>
</header>

<div class="w3-large">
  <div class="divider"></div>

  <h1 class="w3-center w3-padding-64 text-cardapio">CARDÁPIO</h1>

  <div class="w3-container">
    <div class="w3-content" style="max-width:900px">
      <div class="container">
        <div class="text-content">
          <h2 id="titulo-bebidas">Bebidas</h2>
          <p id="texto-p">Nossas bebidas são preparadas com <span class="highlight">carinho e atenção</span>, utilizando <span class="highlight">ingredientes frescos e de qualidade</span>, com o objetivo de proporcionar uma <span class="highlight">experiência acolhedora</span> e única. Oferecemos desde o clássico café expresso até opções mais sofisticadas, todas preparadas com técnicas cuidadosas em um ambiente rústico e humanizado.</p>
        </div>
        <div class="image-content">
         <a href="bebidas.php"> <img src="img/bebidas.png" alt="Bebida deliciosa"></a>
        </div>
      </div>
    </div>
  </div>

  <div style="text-align: center;">
    <img src="img/divisoria.png" alt="Divisória" class="img-divisoria">
  </div>
  

  <div class="w3-container">
    <div class="w3-content" style="max-width:900px">
      <div class="container">
        <div class="text-content">
          <h2 id="titulo-bolos">Bolos</h2>
          <p id="texto-p">Nossos bolos são feitos com <span class="highlight">ingredientes selecionados</span>, de forma <span class="highlight">artesanal</span>, preservando sua naturalidade e sabor autêntico. Cada receita é preparada com muito <span class="highlight">cuidado e dedicação</span>, utilizando apenas produtos frescos, sem aditivos ou conservantes, para garantir uma experiência genuína de sabor. </p>
        </div>
        <div class="image-content">
          <a href="bolos.php"><img src="img/bolo.png" alt="Bolo delicioso"></a>
        </div>
      </div>
    </div>
  </div>

  <div style="text-align: center;">
    <img src="img/divisoria.png" alt="Divisória" class="img-divisoria">
  </div>
  

  <div class="w3-container">
    <div class="w3-content" style="max-width:900px">
      <div class="container">
        <div class="text-content">
          <h2 id="titulo-salgados">Salgados</h2>
          <p id="texto-p">Nossos salgados são preparados com ingredientes frescos e de qualidade, garantindo <span class="highlight">sabores autênticos e irresistíveis</span>. Oferecemos opções tradicionais e criativas, todas feitas com <span class="highlight">cuidado e dedicação</span>, em um ambiente acolhedor e cheio de personalidade.</p>
        </div>
        <div class="image-content">
         <a href="salgados.php"> <img src="img/salgados.png" alt="Salgado"></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container-about w3-container w3-padding-64">
    <div class="text-content-about">
      <h2 class="title-about">Sobre nós</h2>
      <p>Na Coopin, acreditamos que um <strong>bom café vai além do sabor</strong> – é um convite para desacelerar, compartilhar histórias e se conectar. Trabalhamos com <strong>grãos selecionados</strong>, métodos de preparo artesanais e um ambiente aconchegante, pensado para que <strong>cada visita seja especial</strong>. Além de um cardápio repleto de cafés especiais, também oferecemos opções de doces, salgados e refeições leves, preparados com ingredientes frescos e selecionados.</p>
    </div>
    <div class="image-container">
      <div class="circle">
        <img src="img/sobre.png" alt="Barista" id="barista">
      </div>
    </div>
  </div>

  <div style="text-align: center;">
    <img src="img/divisoria.png" alt="Divisória" class="img-divisoria">
  </div>
  

  <h1 class="w3-center w3-padding-64 text-cardapio">NOSSO ESPAÇO</h1>
  <div id="space">
    <img src="img/Group 9.png" alt="">
    <br>
    <br>
    <img src="img/Group 7.png" alt="">
    <br>
    <br>
    <img src="img/Group 8.png" alt="">
    <br><br><br><br>
  </div>

</div>

<script src="js/navbar.js"></script>
<script>
  document.getElementById("myLink")?.click();
</script>

</body>
</html>