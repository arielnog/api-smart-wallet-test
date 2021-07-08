## Instalação

``` bash

# Instale as dependências
$ composer install

```

Copie o arquivo ".env.example", e mude o nome do mesmo para ".env".
Dentro do arquivo ".env" complete a seguinte configuração de acordo com seu banco de dados:

* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=laravel
* DB_USERNAME=root
* DB_PASSWORD=

Para teste do envio de e-email, é sugerido criar uma conta no <a href="https://mailtrap.io/">Mailtrap<a/> e substituir as variaveis de ambiente, exemplo:

* MAIL_MAILER=smtp
* MAIL_HOST=smtp.mailtrap.io
* MAIL_PORT=2525
* MAIL_USERNAME=eda6942abdc20a
* MAIL_PASSWORD=7d4bdc7da1f093
* MAIL_ENCRYPTION=tls

### Proximo passo

``` bash
# Execute (Obs: talvez seja necessário liberar algumas extensões no seu php.ini):
$ php artisan key:generate

$ php artisan jwt:secret

$ php artisan migrate:refresh --seed

```
Após realizar estes comandos, você está pronto para subir o servidor da API.

``` bash
# Execute o comando para subir o servidor do back-end:
$ php artisan serve

```


## Endpoints

Utilizando o Insomnia ou Postman, você poderá realizar os tests da API através destes endpoints: 


POST /auth/create-user
``` json
Envio
{
	"name": "Teste",
	"cpf_cnpj": "00433447044",
	"email": "exemplo@exemplo.com",
	"password": "12345678",
	"role": 1 
}
    
Tipos de Roles:
    role: 1 => lojista
    role: 2 => consumidor

Retorno esperado
{
  "mensagem": "Usuário Criado com Sucesso!"
}

```

POST /auth/login
``` json
Envio 
{
	"email": "exemplo@exemplo.com",
	"password": "12345678",
}

Retorno esperado
{
     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyNTc0NzU0MiwiZXhwIjoxNjI1NzUxMTQyLCJuYmYiOjE2MjU3NDc1NDIsImp0aSI6IlNiTHdjajUwRDM4NUhBZEgiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.g0o01kHWYZTx1NRxrLI2oBoh4npscTkW_H3d_v_xd-8",
  "token_type": "bearer",
  "expires_in": 3600
}
```

POST /auth/logout
``` json
Retorno esperado
{
    "mensagem": "Logout realizado com sucesso"
}
```

GET /wallet/get-balance
``` json
Retorno esperado
{
  "value": "R$150.001,60"
}
```

POST /wallet/do-deposit
``` json
Envio 
{
    "value":"10000.10"
}

Retorno esperado
{
  "mensagem": "Deposito realizado com sucesso!"
}
```

POST /wallet/do-transfer
``` json
Envio 
{
	"email": "exemplo@teste.com.br",
	"value": 5000.00,
	"message": "tes tes tes tes teste"
}

Retorno esperado
{
  "mensagem": "Transferencia realizada com sucesso"
}
```

## Criador

**Ariel Rocha Nogueira**



