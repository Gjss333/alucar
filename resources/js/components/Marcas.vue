<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o Id da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                </input-container-component>  
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="NomeHelp" texto-ajuda="Opcional. Informe o Nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da Marca">
                                </input-container-component> 
                            </div>
                        </div>
                    </template> 

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>

                <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-end"  data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
    </div>


    <modal-component id="modalMarca" titulo="adicionar marca">
        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o Nome da marca">
                    <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                </input-container-component>
                {{ nomeMarca }}
            </div>
        
            <div class="form-group">
                <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="selecione uma imagem em formato png">
                    <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
        {{ arquivoImagem }}
    </modal-component>

</template>

<script>
    export default{
        computed: {
                token(){
                    
                    let token = document.cookie
                    console.log(token)
                    return 'teste'
                }
            },

        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: []
            }
        },
        methods: {
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                console.log(this.nomeMarca, this.arquivoImagem[0])

                let formData = new FormData();
                formData.append('nome', this.nomeMarca) 
                formData.append('imagem', this.arquivoImagem[0])
                
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        // sempre mudar o token
                        'Authorization': this.token

                    }
                }

                axios.post(this.urlBase, formData, config )
                    .then(response => {
                        console.log(response)
                    })
                    .catch(errors)
            }
        }
    }
</script>
