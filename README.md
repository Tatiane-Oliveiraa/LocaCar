# **LocaCar**

O projeto **LocaCar** é uma aplicação web desenvolvida para gerenciar o aluguel de carros. Ele permite que os usuários possam acessar e gerenciar clientes, alugar carros, além de consultar informações sobre carros e aluguéis.

## **Funcionalidades**

* **Cadastro de Clientes**: Permite o registro de novos clientes.
* **Aluguel de Carros**: Permite que o usuário registre o aluguel de um carro.
* **Consulta de Clientes**: Exibe uma lista dos clientes cadastrados.
* **Consulta de Carros**: Exibe os carros disponíveis para aluguel.
* **Consulta de Aluguéis**: Exibe todos os aluguéis registrados na plataforma.

## **Tecnologias Usadas**

* **HTML**: Estrutura da página.
* **CSS**: Estilos visuais para a interface.
* **PHP**: Backend para gerenciar clientes, aluguéis e carros.
* **JavaScript**: Interatividade na navegação (com a utilização de `onclick`).

## **Estrutura do Projeto**

```plaintext
LocaCar/
├── src/
│   ├── css/
│   │   └── style.css   # Arquivo CSS para estilos da página
│   ├── php/
│   │   ├── clientes.php         # Página para cadastro de clientes
│   │   ├── alugar.php           # Página para aluguel de carros
│   │   ├── consultar_clientes.php  # Página para consultar clientes
│   │   ├── consultar_carros.php    # Página para consultar carros
│   │   └── consultar_alugueis.php  # Página para consultar aluguéis
├── logo.png           # Logo do projeto
├── index.html         # Página inicial da aplicação
└── README.md          # Este arquivo
```

## **Como Usar**

1. **Clonar o repositório**:
   Clone o repositório para o seu ambiente local ou servidor web.

   ```bash
   git clone <URL_DO_REPOSITORIO>
   ```

2. **Configuração do Servidor Web**:
   Para rodar o projeto, você pode configurar um servidor local utilizando o [XAMPP](https://www.apachefriends.org/pt_br/index.html) ou o [MAMP](https://www.mamp.info/pt/).

3. **Acessar a Aplicação**:
   Após configurar o servidor, abra o navegador e acesse a página principal:

   ```plaintext
   http://localhost/LocaCar/index.html
   ```

4. **Interatividade**:
   Na página inicial, você pode clicar nos botões para:

   * **Clientes**: Acesse a página para cadastrar novos clientes.
   * **Alugar Carro**: Registre um novo aluguel de carro.
   * **Consultar Clientes**: Veja a lista de clientes cadastrados.
   * **Consultar Carros**: Consulte os carros disponíveis para aluguel.
   * **Consultar Aluguéis**: Veja os aluguéis registrados.

## **Pré-requisitos**

Certifique-se de ter os seguintes programas instalados em seu sistema:

* Um servidor web local, como **XAMPP** ou **MAMP**.
* **PHP** instalado no servidor.

## **Considerações Finais**

O **LocaCar** é um sistema simples para gerenciar o aluguel de carros, com funcionalidades básicas de cadastro e consulta. Este projeto pode ser expandido com novas funcionalidades, como o pagamento online, integração com bancos de dados, entre outros.
