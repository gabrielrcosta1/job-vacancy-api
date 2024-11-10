
# Teste Técnico - Desenvolvedor Backend Pleno Laravel

## Descrição do Projeto
Desenvolver uma API REST para um sistema de vagas de emprego, onde empresas podem gerenciar suas vagas e candidatos podem visualizar e se candidatar às vagas disponíveis.

## Requisitos Técnicos
- Laravel 10+
- PHP 8.1+
- MySQL/PostgreSQL

## Estrutura do Projeto
O projeto segue uma arquitetura em camadas para melhor organização do código e separação de responsabilidades:
- **Controllers**: Camada de controle das requisições.
- **Services**: Responsável pelas regras de negócio.
- **DTOs**: Utilizado para entrada de dados complexos, com propriedades tipadas.
- **Resources**: Transformação das respostas da API.
- **Models**: Representação dos dados do sistema.
- **Database**: Contém migrations e seeders para estrutura e dados de teste.
- **Tests**: Cobertura de testes para garantir a funcionalidade.

## Funcionalidades

### Empresa (Header obrigatório: X-Company-ID)

1. **Criar Vaga**
   - Endpoint: `POST /api/jobs`
   - Validação via `FormRequest`
   - Campos obrigatórios: título, descrição, faixa salarial (min/max), requisitos, benefícios
   - Retorna: `VacancyResource` com os dados da vaga criada.

2. **Atualizar Vaga**
   - Endpoint: `PUT /api/jobs/{id}`
   - Verifica se a vaga pertence à empresa e impede atualização caso haja candidatos.
   - Retorna: `VacancyResource` atualizado.

3. **Listar Vagas da Empresa**
   - Endpoint: `GET /api/jobs`
   - Paginação obrigatória e filtros opcionais por status e data de criação.
   - Retorna: `VacancyCollection` com informações da vaga e número de candidatos.

4. **Deletar Vaga**
   - Endpoint: `DELETE /api/jobs/{id}`
   - Realiza soft delete, não permitindo deleção se houver candidatos.

### Candidato (Header obrigatório: X-User-ID)

1. **Listar Vagas Disponíveis**
   - Endpoint: `GET /api/jobs/available`
   - Paginação obrigatória e filtros opcionais por faixa salarial e palavra-chave.
   - Não mostra vagas inativas.
   - Retorna: `VacancyCollection` com vagas disponíveis.

2. **Candidatar-se à Vaga**
   - Endpoint: `POST /api/jobs/{id}/apply`
   - Valida se a vaga está ativa e se o candidato não está previamente aplicado.
   - Implementa toggle de candidatura.
   - Retorna: `ApplicationResource` com status da candidatura.

3. **Listar Minhas Candidaturas**
   - Endpoint: `GET /api/applications`
   - Retorna: `ApplicationCollection` com informações das vagas e datas de candidatura.

## Requisitos Técnicos Específicos

- **Resources**: `VacancyResource` e `VacancyCollection` para padronizar as respostas das vagas, `ApplicationResource` e `ApplicationCollection` para candidaturas.
- **DTOs**: Criados para entradas de dados complexas com propriedades tipadas.
- **Database**: Migrations e seeders para estrutura e dados de teste.
- **Testes de Integração**: Cobertura de criação, atualização e processo de candidatura, validando o formato de respostas.

## Docker e Ambiente
O projeto utiliza Docker para rodar o PostgreSQL. Um arquivo `docker-compose.yml` está disponível no repositório Git, facilitando a inicialização do ambiente.

### Passos para rodar o projeto
1. Clone o repositório.
2. Execute `docker-compose up -d` para iniciar o PostgreSQL.
3. Instale as dependências com `composer install`.
4. Configure o arquivo `.env` para apontar para o banco de dados no Docker.
5. Execute as migrations com `php artisan migrate`.
6. Inicie o servidor com `php artisan serve`.
