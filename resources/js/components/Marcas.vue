<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o Id da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>  
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="NomeHelp" texto-ajuda="Opcional. Informe o Nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                                </input-container-component> 
                            </div>
                        </div>
                    </template> 
            
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            :dados="marcas.data"
                            :visualizar="true"
                            :atualizar="true"
                            :remover="true"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                nome: {titulo: 'Nome', tipo: 'text'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Data de Criação', tipo: 'data'},
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end"  data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>
    </div>


    <modal-component id="modalMarca" titulo="adicionar marca">

        <template v-slot:alertas>
            <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component> 
            <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo ="Error ao Tentar Cadastrar a marca" v-if="transacaoStatus == 'Erro'"></alert-component> 
        </template>

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
            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
        {{ arquivoImagem }}
    </modal-component>

</template>

<script>
    export default{
        computed: {
                token(){
                    let token = document.cookie.split(';').find(indice => {
                        return indice.startsWith('token=')
                    })

                    token = token.split('=')[1]
                    token = 'Bearer ' + token
                    return token
                }
            },

        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: { data: [] },
                busca: { id: '', nome: '' },
                urlPaginacao: '',
                urlFiltro: ''
            }
        },
        methods: {
            pesquisar(){
                // console.log(this.busca)

                let filtro = ''

                for(let chave in this.busca){

                    if(this.busca[chave]){

                        if(filtro != ''){
                            filtro += ';'
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }

                }
                if(filtro != ''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro+'%'
                }else{
                    this.urlFiltro = ''
                }
                this.carregarLista()
            },
            paginacao(l){
                if(l.url){

                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarLista()
                }
            },

            carregarLista(){

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(url, config)
                    .then(response => {
                        this.marcas = response.data
                        // console.log(response.data)
                        
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },

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
                        this.transacaoStatus = 'Adicionado'
                        this.transacaoStatus = {
                            mensagem:'ID do registro ' + response.data.id 
                        } 
                    
                        
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'Erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message ,
                            dados: errors.response.data.errors   
                        }
                        console.log(errors.response.data.message)
                    })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>
