<template>
    <label :for="id" class="form-label">{{titulo}}</label>
    <div class="row p-1 g-3">
        <slot></slot>
        <div :id="idHelp" class="form-text text-muted">{{textoAjuda}}</div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo', 'idHelp', 'textoAjuda']
    }
</script>