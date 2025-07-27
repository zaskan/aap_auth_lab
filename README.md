# Ansible Automation Platform Auth Lab
## Overview
This repository provides two roles in order to bring up an LDAP and a SAML podman containers in an easy way.
It also configures the proper settings in an Ansible Automation Platform in order to integrate with them.
The purpose is to serve an authentication environment for testing or troubleshooting.
### Requirements
- ansible.controller collection version 4.5.X for Ansible Controller 2.4
- ansible.controller collection version 4.6.X for Ansible Automation Gateway 2.5 (To do)
- containers.podman collection
- Podman 
- An Ansible Automation Platform Instance running. Refer to the [docs](https://docs.redhat.com/en/documentation/red_hat_ansible_automation_platform/2.5)
- The containers use the following images which must be reachable:
  - docker.io/osixia/openldap:1.5.0
  - docker.io/kenchan0130/simplesamlphp

You can install the collections using the following command from the root of the project. Modify the versions in the requirements file according to the notes above.
```
ansible-galaxy collection install -r collections/requirements.yml
```
### Installation

There are some default settings that you can change if you need. For the ldap role, these are the defgault settings:

For LDAP:

```
    ldap_image: docker.io/osixia/openldap:1.5.0 
    ldap_container_name: testldap
    ldap_ports:
      - "389:389"
      - "636:636"
    ldap_org: "Example Inc."
    ldap_domain: "example.org"
    ldap_admin_password: "admin"
    ldap_bootstrap_ldif: "bootstrap.ldif"
    container_bootstrap_ldif: "/ldif/50-bootstrap.ldif"
    ldap_server: "ldap://10.0.0.101:389"
```

Probably you will need to adapt the following:

**ldap_server** is the host running the ldap container and the exposed port

For SAML:

```
    idp_host: "http://10.0.0.101:8080"
    entity_url: "https://192.168.122.82/sso/complete/saml/"
```

Probably you will need to adapt the following:

**idp_host** is the host running the saml container and the exposed port
**entity_url** is the host running the ansible automation platform and the url for the completed saml transaction.

In order to execute the roles, you need a playbook similar to this one.

```
---
- name: Run OpenLDAP container with Podman (parameterized, plain-text password)
  hosts: localhost
  become: true
  vars:
    ldap_image: docker.io/osixia/openldap:1.5.0
    ldap_container_name: testldap
    ldap_ports:
      - "389:389"
      - "636:636"
    ldap_org: "Example Inc."
    ldap_domain: "example.org"
    ldap_admin_password: "admin"
    ldap_bootstrap_ldif: "bootstrap.ldif"
    container_bootstrap_ldif: "/ldif/50-bootstrap.ldif"
    ldap_server: "ldap://10.0.0.101:389"

  tasks:
    - name: Bring up LDAP container and configure the Ansible Controller to use it
      ansible.builtin.include_role:
        name: ldap

    - name: Bring up SAML container and configure the Ansible Controller to use it
      ansible.builtin.include_role:
        name: saml
      vars:
        idp_host: "http://10.0.0.101:8080"
        entity_url: "https://192.168.122.82/sso/complete/saml/"
```

Set your own settings and use `ansible-playbook` or `ansible-navigator` to run it:

```
ansible-playbook main.yml
```

### Testing

Four users are created in total after the execution of both roles.

| username | type   | password  |
|----------|--------|-----------|
| user1    | saml   | user1pass |
| user2    | saml   | user2pass |  
| user3    | ldap   | user3pass |
| user4    | ldap   | user4pass | 

Access your Ansible Automation Platform instance and try to login using these credentials.

If you need to troubleshoot, enable **DEBUG** in you Ansible Automation Platform logger settings and monitor the file `/var/log/tower/tower.log`

### TODO

- Add code for 2.5