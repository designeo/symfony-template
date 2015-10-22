## Příprava

natáhneme závislosti:

`ansible-galaxy install -r requirements.yml -p .`

## Servery

přidáme adresu serveru do inventory/[env].ini:

```
[prod]
symfony-designeo.cz ansible_ssh_user=root
```

konfigurace jednotlivých prostředí je v group_vars.

## Deploy

`ansible-playbook -i inventory/local.ini playbook.yml`
