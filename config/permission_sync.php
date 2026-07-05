<?php

return [

    'permissions' => [
        'users.view' => 'Visualizar usuário',
        'users.update' => 'Atualizar usuário',

        'admin.view' => 'Visualizar administrador',
        'admin.create' => 'Criar administrador',
        'admin.update' => 'Atualizar administrador',
        'admin.delete' => 'Deletar administrador',
        'admin.destroy' => 'Destroi administrador',
        'admin.restore' => 'Restaura administrador',

        'tenant.view' => 'Visualizar inquilino',
        'tenant.create' => 'Criar inquilino',
        'tenant.update' => 'Atualizar inquilino',
        'tenant.delete' => 'Deletar inquilino',
        'tenant.destroy' => 'Destroi inquilino',
        'tenant.restore' => 'Restaura inquilino',

        'permissions.view' => 'Visualizar permissoes',


        'roles.view' => 'Visualizar cargos',
        'roles.assign-permission' => 'Atribuir permissao ao cargo',
    ],

    'module_labels' => [
        'users' => 'Usuários',
        'admin' => 'Administradores',
        'permissions' => 'Permissoes',
        'roles' => 'Cargos',
        'tenant' => 'Inquilinos',
    ],
];
