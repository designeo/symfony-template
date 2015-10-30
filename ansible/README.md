# Ansible

## Requirements

- Install Ansible - `sudo easy_install pip` and `sudo pip install ansible` (http://docs.ansible.com/ansible/intro_installation.html#latest-releases-via-pip)
- Install VirtualBox - https://www.virtualbox.org/wiki/Downloads
- Install Vagrant - http://www.vagrantup.com/downloads
- Install Vagrant plugins - `vagrant plugin install vagrant-bindfs` a `vagrant plugin install vagrant-share`

## Fetch Ansible requirements

- Run `ansible-galaxy install -r requirements.yml -p .` in ansible folder

## Local development

- Run `vagrant up` in project root folder. It will create new virtul machine and run ansible playbook.
- If the virtual machine is already running, run `vagrant provision`.
- `vagrant reload` will restart the virtual machine
- `vagrant destroy` will completelly delete the virtual machine


## Servers

přidáme adresu serveru do inventory/[env].ini:

```
[prod]
symfony-designeo.cz ansible_ssh_user=root
```

konfigurace jednotlivých prostředí je v group_vars.

## Deploy

`ansible-playbook -i inventory/local.ini playbook.yml`
