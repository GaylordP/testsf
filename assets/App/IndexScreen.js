import axios from 'axios'
import React, { useEffect, useState } from 'react'
import { render } from 'react-dom'
import { format as dateFormat } from 'date-fns'
import { fr as dateFr } from 'date-fns/locale'

const getArticles = async (loader, successCallback) => {
    try {
        const response = await axios.get('http://localhost:8082/api/articles')

        loader()
        successCallback(response.data['hydra:member'])
    } catch {
        loader()
    }
}

const deleteArticle = async (articleSlug, successCallback) => {
    try {
        await axios.delete('http://localhost:8082/api/articles/' + articleSlug)

        successCallback()
    } catch (e) {
        console.log(e)
    }
}

const Loader = () => {
    return (
        <tr>
            <td className="text-center" colSpan={4}>
                <div className="spinner-border" role="status">
                    <span className="visually-hidden">
                        Chargement
                    </span>
                </div>
            </td>
        </tr>
    )
}

const Row = ({ article }) => {
    return (
        <tr id={'article-' + article.id}>
            <td>
                {article.title}
            </td>
            <td>
                {dateFormat(new Date(article.createdAt), 'PPPp', {locale: dateFr})}
            </td>
            <td>
                {article.createdBy}
            </td>
            <td>
                <a
                    href={'/article/' + article.slug}
                    className="btn btn-sm btn-primary me-1"
                >
                    Consulter
                </a>
                <a
                    onClick={() => {
                        deleteArticle(
                            article.slug,
                            () => document.querySelector('#article-' + article.id).remove()
                        )
                    }}
                    className="btn btn-sm btn-danger"
                >
                    Supprimer
                </a>
            </td>
        </tr>
    )
}

const IndexScreen = () => {
    const [loader, setLoader] = useState(false)
    const [articles, setArticles] = useState([])

    useEffect(() => {
        setLoader(true)

        const articles = getArticles(
            () => setLoader(false),
            (datas) => setArticles(datas)
        )
    }, [])

    return (
        <>
            {
                true === loader && <Loader />
            }
            {articles.map((article) => {
                return (
                    <Row
                        article={article}
                        key={article.id}
                    />
                )
            })}
        </>
    )
}

render(<IndexScreen />, document.getElementById('table-content'))
