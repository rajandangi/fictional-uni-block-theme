import "./index.scss"
import { useState, useEffect } from 'react';
import { Spinner } from '@wordpress/components';
import { useSelect } from "@wordpress/data";
import apiFetch from '@wordpress/api-fetch';
import { store as coreDataStore } from '@wordpress/core-data';
const __ = wp.i18n.__; // The __() for internationalization.

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profID: { type: "string" },
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("");

  // When the profID attribute changes, update the meta field and get the HTML
  useEffect(() => {
    if (props.attributes.profID) {
      updateTheMeta();
      // Call Custom API endpoint to get the HTML
      async function go() {
        const response = await apiFetch({
          path: `/featuredProfessor/v1/getHTML?profID=${props.attributes.profID}`,
          method: 'GET'
        });
        setThePreview(response);
      }
      go();
    }
  }, [props.attributes.profID])

  // When the block is removed, update the meta field
  useEffect(() => {
    return () => {
      updateTheMeta();
    }
  }, []);

  function updateTheMeta() {
    // Get all the blocks on the page, filter for our block, get the profID attribute, and remove duplicates
    const profsForMeta = wp.data.select("core/block-editor")
      .getBlocks()
      .filter(block => block.name == "ourplugin/featured-professor")
      .map(block => block.attributes.profID)
      .filter((block, index, arr) => {
        return arr.indexOf(block) === index;
      });

    // Update the meta field, which is already registered in our featured-professor.php file
    wp.data.dispatch("core/editor").editPost({ meta: { featuredprofessor: profsForMeta } });
  }

  const { allProfs, hasResolved } = useSelect(
    (select) => {
      const selectorArgs = ['postType', 'professor', { per_page: -1 }];
      return {
        allProfs: select(coreDataStore).getEntityRecords(...selectorArgs),
        hasResolved: select(coreDataStore).hasFinishedResolution('getEntityRecords', selectorArgs),
      };
    });

  if (!hasResolved) {
    return <Spinner />;
  }

  if (!allProfs?.length) {
    return <div>No results</div>;
  }

  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={e => props.setAttributes({ profID: e.target.value })}>
          <option value="">{__('Select a professor', 'featured-professor')}</option>
          {allProfs.map(prof => {
            return (
              <option value={prof.id} selected={props.attributes.profID == prof.id}>{prof.title.rendered}</option>
            )
          })}
        </select>
      </div>
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  )
}