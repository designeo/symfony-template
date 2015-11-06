# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "debian/wheezy64"
  #config.vm.box = "debian/jessie64"

  config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.111.100"

  config.vm.synced_folder ".", "/vagrant",
    nfs: true
    #map_uid: "vagrant",
    #map_gid: "www-data"
    #group: "www-data",
    #mount_options: ["dmode=775,fmode=775"]

  config.bindfs.bind_folder "/vagrant", "/srv/app",
    perms: "u=rwx:g=rwx:o=rx",
    owner: "vagrant",
    group: "www-data"

  config.vm.provision "shell", inline: "apt-get install --yes python-apt"

  config.vm.provision "ansible" do |ansible|

    ansible.groups = {
        "local" => ["default"],
        #"dev" => ["default"]
    }

    #ansible.playbook = "ansible/testplaybook.yml"
    ansible.playbook = "ansible/playbook.yml"
    ansible.verbose = "vvv"
  end
end
