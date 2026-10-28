# Guia Comercial de Bairro — Aplicação Web Portátil

Uma aplicação web leve, moderna e autossuficiente para diretórios e comércios locais, desenvolvida em **PHP puro** e **SQLite**. O sistema foi projetado com foco total em alta performance de busca, portabilidade e facilidade de implantação sem dependências complexas.

---

## 🚀 Arquitetura & Destaques Técnicos

- **Arquitetura MVC Descomplicada**: Separação clara de responsabilidades entre Regras de Negócio (`src/`), Controladores/Processadores (`src/handlers/`) e Apresentação (`src/views/`).
- **Busca Normalizada e Tolerante**:
  - Uso de colunas de busca otimizadas (`_clean_...`) preenchidas automaticamente nas operações de cadastro e edição.
  - Normalização e remoção de acentos, maiúsculas e caracteres especiais diretamente via aplicação.
  - Filtro dinâmico com operador relacional flexível (`AND` / `OR`) e tratamento especial para caracteres numéricos (evitando falsos positivos em buscas por telefones/WhatsApp).
- **Gerenciamento Transacional em Lote (`src/Merchant.php`)**: Suporte a inserções individuais e em massa utilizando transações nativas SQLite (`beginTransaction` / `commit`), garantindo atomicidade e velocidade.
- **Zero Configuração Externa**: Configurações globais e credenciais do painel são mantidas na tabela interna `settings` no próprio banco SQLite.
- **Roteamento Dinâmico por Caminhos Absolutos (`src/paths.php`)**: Mapeamento centralizado que elimina problemas de inclusão e garante portabilidade total em qualquer ambiente web.

---

## 📂 Estrutura do Projeto

```text
.
├── run                       # CLI para executar ferramentas de tools/
├── README.md                 # Documentação do projeto
├── public/
│   ├── index.php             # Front Controller / ponto de entrada web
│   └── assets/
│       ├── css/              # Estilos da aplicação e Pico CSS
│       └── icons/            # Ícones SVG usados na interface
├── src/
│   ├── Auth.php              # Autenticação e sessão
│   ├── Database.php          # Conexão PDO e inicialização do SQLite
│   ├── Merchant.php          # Regras de negócio e consultas de comerciantes
│   ├── Setting.php           # Leitura e gravação de configurações
│   ├── paths.php             # Mapeamento centralizado de diretórios
│   ├── router.php            # Seleção e carregamento do handler
│   ├── utils.php             # Funções auxiliares
│   ├── handlers/             # Processamento das requisições por página
│   │   ├── admin.php
│   │   ├── list.php
│   │   ├── login.php
│   │   └── setup.php
│   └── views/
│       ├── components/       # Formulários, cartões, grade e paginação
│       ├── pages/             # Páginas de administração, lista, login e setup
│       └── partials/          # Cabeçalho, head e rodapé compartilhados
├── tools/
│   ├── seed.php              # Importação de dados iniciais
│   └── server.php            # Inicialização do servidor de desenvolvimento
```

Os arquivos locais de dados, como o banco SQLite e o CSV de importação, são mantidos em `storage/` quando presentes; essa pasta não faz parte dos arquivos versionados listados acima.

---

## 🛠️ Metodologia de Desenvolvimento e Parceria com IA

Este projeto foi desenvolvido utilizando a Inteligência Artificial (Gemini) como **ferramenta de produtividade e aceleração de escrita de código**.

> **Nota de Transparência:** A IA é utilizada primariamente para evitar a digitação manual exaustiva de código boilerplate. Contudo, **a liderança técnica, as decisões de arquitetura e a palavra final são 100% humanas.**

- **Arquitetura e Regras de Negócio**: A concepção da modelagem (ex: estratégia de normalização com colunas `_clean_`), a estruturação dos scripts, os padrões de projeto e o fluxo de dados foram idealizados e decididos inteiramente pelo desenvolvedor.
- **Auditoria e Refatoração Ativa**: Nenhum código gerado é aceito cegamente. Todas as saídas da IA passam por análise minuciosa, testes de borda (como o tratamento de queries vazias ou comportamento de operadores SQL) e refatorações manuais até atingirem a solução ideal.
