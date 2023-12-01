import React, { useEffect, useState } from "react";
import Pagination from 'react-bootstrap/Pagination';
import '../../App.css';

const CustomPagination = ({data, setDataCallback}) => {

    const [amount, setAmount] = useState(0);
    const [pageSize, setPageSize] = useState(10);
    const [items, setItems] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);

    useEffect(() => {
        const pages = Math.ceil(data.length / pageSize);
        var array = [];
        for (var i=0; i<pages; i++) {
            array.push(i + 1);
        }
        setItems(array);
        setAmount(array.length);
    }, []);

    const getPageItem = (index) => {
        if (currentPage === index) {
            return (<Pagination.Item active key={index}>{index}</Pagination.Item>);  
        }
        return (<Pagination.Item onClick={() => goTo(index)} key={index}>{index}</Pagination.Item>);
    };

    const goTo = (page) => {
        if (page <= 0) {
            setCurrentPage(1);
            setDataCallback(1, pageSize, data);
            return;
        }
        if (page > amount) {
            setCurrentPage(amount);
            setDataCallback(amount, pageSize, data);
            return;
        }
        setCurrentPage(page);
        setDataCallback(page, pageSize, data);
    };

    function getPagesToBeDisplayed() {
        if (items.length <= 10) {
            return items.map((item) => 
                getPageItem(item)
            );   
        }

        var currentIndex = currentPage - 1;
        var endIndex = currentPage;

        for (var i=currentIndex; i<=currentIndex + 9; i++) {
            if (items[i]) {
                endIndex = i;
            }
        }

        if (currentIndex === endIndex) {
            endIndex += 1;
        }

        var list = items.slice(currentIndex, endIndex);
        var pageItems = list.map((item) => 
            getPageItem(item)
        );

        if (currentPage < amount) {
            pageItems.push((<Pagination.Ellipsis></Pagination.Ellipsis>));
        }
        return pageItems;
    }

    return (
        <>
        <Pagination>
            <Pagination.First onClick={() => goTo(1)} />
            <Pagination.Prev onClick={() => goTo(currentPage - 1)} />

            {getPagesToBeDisplayed()}

            <Pagination.Next onClick={() => goTo(currentPage + 1)} />
            <Pagination.Last onClick={() => goTo(amount)} />
        </Pagination>
        </>
    );
};

export default CustomPagination;
